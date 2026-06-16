<?php

namespace App\Http\Controllers;

use App\Models\Motor;
use App\Models\Perlengkapan;
use App\Models\Rental;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Midtrans\Config;
use Midtrans\Snap;

class RentalController extends Controller
{

    public function index(Request $request)
    {
        // if (Auth::user()->role === 'admin') {
        //     return redirect('/admin');
        // }
        $userId = Auth::id();

        $rentals = Rental::where('user_id', Auth::id())->with('motor')->latest()->paginate(10);

        $allUserOrders = Rental::where('user_id', $userId)->get();

        $counts = [
            'all'       => $allUserOrders->count(),
            'Menunggu'   => $allUserOrders->where('status', 'Menunggu')->count(),
            'Disewa'    => $allUserOrders->where('status', 'Disewa')->count(),
            'Selesai' => $allUserOrders->where('status', 'Selesai')->count(),
            'Gagal' => $allUserOrders->where('status', 'Gagal')->count(),
        ];

        $query = Rental::with('motor')->where('user_id', $userId);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $rentals = $query->latest()->paginate(10);

        return view('list-rental', compact('rentals', 'counts'));
    }

    public function checkout(Motor $motor)
    {
        if ($motor->status !== 'Tersedia') {
            return redirect()->back()->with('error', 'Motor ini sedang tidak tersedia.');
        }

        return view('customer.rentals.checkout', compact('motor'));
    }

    public function store(Request $request)
    {
        // Validasi Akun Terverifikasi
        if (!Auth::user()->is_verified) {
            return redirect()->route('profile.index')
                ->with('error', 'Silakan lengkapi profil dan tunggu verifikasi KTP/SIM sebelum menyewa.');
        }

        // Batasan Limit Sewa Akun
        $user = Auth::user();
        $activeRentalsCount = Rental::where('user_id', $user->id)
            ->whereIn('status', ['Menunggu', 'Disewa'])
            ->count();

        if ($activeRentalsCount >= $user->rental_limit) {
            return redirect()->back()
                ->with('error', "Batas sewa tercapai. Kamu hanya diperbolehkan menyewa maksimal {$user->rental_limit} motor secara bersamaan.");
        }

        // Validasi Request
        $request->validate([
            'motor_id'                => 'required|exists:motors,id',
            'tanggal_mulai'           => 'required',
            'tanggal_rencana_kembali' => 'required',
            'total_harga'             => 'required|numeric',
        ]);

        if (!empty($request->input('perlengkapan_ids'))) {
            foreach ($request->input('perlengkapan_ids') as $idPerlengkapan) {
                $perlengkapan = \App\Models\Perlengkapan::find($idPerlengkapan);

                if ($perlengkapan && $perlengkapan->stok <= 0) {
                    return redirect()->back()->with('error', "Maaf, perlengkapan '{$perlengkapan->nama_perlengkapan}' baru saja kehabisan stok!");
                }
            }
        }

        // Cek Ketersediaan Motor
        $motor = Motor::findOrFail($request->motor_id);
        $isAvailable = Motor::where('id', $request->motor_id)
            ->where('status', 'Tersedia')
            ->exists();

        if (!$isAvailable) {
            return redirect()->back()->with('error', 'Maaf, motor baru saja dipesan orang lain!');
        }

        $tglMulai   = Carbon::parse($request->tanggal_mulai)->format('Y-m-d H:i:s');
        $tglKembali = Carbon::parse($request->tanggal_rencana_kembali)->format('Y-m-d H:i:s');

        $kodeBooking = 'KBB-' . date('Ymd') . '-' . strtoupper(Str::random(5));

        $hargaFinalYangDisimpan = $request->total_harga;

        $rental = Rental::create([
            'user_id'                 => Auth::id(),
            'motor_id'                => $motor->id,
            'kode_booking'            => $kodeBooking,
            'tanggal_mulai'           => $tglMulai,
            'tanggal_rencana_kembali' => $tglKembali,
            'total_harga'             => $hargaFinalYangDisimpan,
            'penalty'                 => 0,
            'status'                  => 'Menunggu',
            'payment_proof'           => null,
            'metode_pengantaran'      => $request->metode_pengantaran,
            'alamat_pengantaran'      => $request->metode_pengantaran === 'delivery' ? $request->alamat_pengantaran_final : null,
        ]);

        $perlengkapanDipilih = $request->input('perlengkapan_ids');

        if (!empty($perlengkapanDipilih)) {
            $rental->perlengkapan()->attach($perlengkapanDipilih);

            foreach ($perlengkapanDipilih as $idPerlengkapan) {
                Perlengkapan::where('id', $idPerlengkapan)->decrement('stok', 1);
            }
        }

        return redirect()->route('customer.orders')
            ->with('success', "Booking motor {$motor->model} berhasil dibuat dengan total Rp " . number_format($hargaFinalYangDisimpan, 0, ',', '.'));
    }

    public function paymentPage($id)
    {
        $rental = Rental::with(['motor', 'user'])->where('user_id', Auth::id())->findOrFail($id);

        if ($rental->status !== 'Menunggu' && $rental->status !== 'waiting') {
            return redirect()->route('customer.orders')->with('error', 'Transaksi ini tidak membutuhkan pembayaran.');
        }

        $isMotorTaken = Rental::where('motor_id', $rental->motor_id)
            ->where('status', 'Disewa')
            ->where(function ($query) use ($rental) {
                $query->whereBetween('tanggal_mulai', [$rental->tanggal_mulai, $rental->tanggal_rencana_kembali])
                    ->orWhereBetween('tanggal_rencana_kembali', [$rental->tanggal_mulai, $rental->tanggal_rencana_kembali])
                    ->orWhere(function ($q) use ($rental) {
                        $q->where('tanggal_mulai', '<=', $rental->tanggal_mulai)
                            ->where('tanggal_rencana_kembali', '>=', $rental->tanggal_rencana_kembali);
                    });
            })
            ->exists();

        if ($isMotorTaken) {
            $rental->update(['status' => 'Gagal']);

            return redirect()->route('payment.failed', $rental->id)
                ->with('error', 'Maaf, unit motor ini baru saja didahului oleh penyewa lain yang membayar lebih cepat!');
        }

        $waktuSekarang = Carbon::now();

        if ($rental->snap_token && $rental->payment_expired_at && $waktuSekarang->lessThan($rental->payment_expired_at)) {
            $snapToken = $rental->snap_token;
        } else {
            \Midtrans\Config::$serverKey = env('MIDTRANS_SERVER_KEY');
            \Midtrans\Config::$isProduction = env('MIDTRANS_IS_PRODUCTION') === 'true';
            \Midtrans\Config::$isSanitized = true;
            \Midtrans\Config::$is3ds = true;

            $duration = 1;
            $unit = 'hour';

            $params = [
                'transaction_details' => [
                    'order_id' => $rental->kode_booking . '-' . time(),
                    'gross_amount' => (int) $rental->total_harga,
                ],
                'customer_details' => [
                    'first_name' => $rental->user->name,
                    'email' => $rental->user->email,
                ],
                'item_details' => [
                    [
                        'id' => $rental->motor_id,
                        'price' => (int) $rental->total_harga,
                        'quantity' => 1,
                        'name' => 'Sewa ' . $rental->motor->brand . ' ' . $rental->motor->model,
                    ]
                ],
                'expiry' => [
                    'start_time' => date("Y-m-d H:i:s O"),
                    'duration' => $duration,
                    'unit' => $unit
                ]
            ];

            $snapToken = \Midtrans\Snap::getSnapToken($params);

            $rental->snap_token = $snapToken;
            $rental->payment_expired_at = \Carbon\Carbon::now()->addHours($duration);
            $rental->save();
        }

        return view('payment', compact('rental', 'snapToken'));
    }

    public function midtransWebhook(Request $request)
    {
        $payload = $request->all();

        $orderId = $payload['order_id'];
        $statusCode = $payload['status_code'];
        $grossAmount = $payload['gross_amount'];
        $transactionStatus = $payload['transaction_status'];
        $type = $payload['payment_type'];
        $signatureKey = $payload['signature_key'];

        $serverKey = env('MIDTRANS_SERVER_KEY');
        $localSignature = hash("sha512", $orderId . $statusCode . $grossAmount . $serverKey);

        $statusMurni = strtolower($transactionStatus);

        Log::info('Midtrans Webhook Masuk. Order ID: ' . $orderId . ' | Status Asli: ' . $transactionStatus . ' | Status Murni: ' . $statusMurni . ' | Amount: ' . $grossAmount);

        if ($signatureKey !== $localSignature) {
            Log::error('Webhook Gagal: Invalid Signature untuk Order ID ' . $orderId);
            return response()->json(['message' => 'Invalid Signature'], 403);
        }

        $isDendaPayment = str_starts_with($orderId, 'DENDA-');

        if ($isDendaPayment) {
            $cleanOrderId = str_replace('DENDA-', '', $orderId);
            $actualOrderId = substr($cleanOrderId, 0, strrpos($cleanOrderId, '-'));
        } else {
            $actualOrderId = substr($orderId, 0, strrpos($orderId, '-'));
        }

        $rental = Rental::where('kode_booking', $actualOrderId)->first();


        if ($statusMurni == 'capture' || $statusMurni == 'settlement') {

            if ($isDendaPayment) {
                Rental::where('id', $rental->id)->update([
                    'status'  => 'Selesai',
                    'penalty' => (int) $grossAmount
                ]);

                if ($rental->motor_id) {
                    Motor::where('id', $rental->motor_id)->update(['status' => 'Tersedia']);
                }
                Log::info('Denda Berhasil Diupdate ke Database untuk Booking: ' . $actualOrderId);
            } else {
                $rental->update(['status' => 'Disewa']);

                if ($rental->motor_id) {
                    Motor::where('id', $rental->motor_id)->update(['status' => 'Disewa']);
                }
            }
        } elseif ($statusMurni == 'pending') {

            if (!$isDendaPayment) {
                $rental->update(['status' => 'Menunggu']);
            }
        } elseif (in_array($statusMurni, ['deny', 'expire', 'cancel'])) {

            if ($isDendaPayment) {
                Rental::where('id', $rental->id)->update([
                    'status' => 'Pending Denda',
                    'snap_token' => null,
                    'payment_expired_at' => null
                ]);
            } else {
                $rental->update([
                    'status' => 'Gagal',
                    'snap_token' => null,
                    'payment_expired_at' => null
                ]);

                if ($rental->motor_id) {
                    Motor::where('id', $rental->motor_id)->update(['status' => 'Tersedia']);
                }
            }
        }

        return response()->json([
            'status' => 'Selesai',
            'message' => 'Status database KudaBesiRent berhasil diperbarui!'
        ], 200);
    }

    public function downloadStruk($id)
    {
        $rental = Rental::with(['motor', 'user'])
            ->where('user_id', Auth::id())
            ->findOrFail($id);

        if (in_array($rental->status, ['Menunggu', 'Batal', 'Gagal'])) {
            return redirect()->back()->with('error', 'Struk belum tersedia untuk transaksi ini.');
        }

        $pdf = Pdf::loadView('struk', compact('rental'));

        return $pdf->download('Struk-' . $rental->kode_booking . '.pdf');
    }

    public function KonfirmasiMotor($id)

    {
        $rental = Rental::with('motor')->findOrFail($id);

        $waktuSekarang = Carbon::now('Asia/Jakarta')->startOfDay();
        $waktuRencanaKembali = Carbon::parse($rental->tanggal_rencana_kembali, 'Asia/Jakarta')->startOfDay();

        $biayaPenalti = 0;
        $selisihHari = 0;

        if ($waktuSekarang->gt($waktuRencanaKembali)) {

            $selisihHari = $waktuRencanaKembali->diffInDays($waktuSekarang);

            if ($selisihHari > 0) {
                $dendaPerHari = 50000;
                $biayaPenalti = ($rental->motor->harga_per_hari + $dendaPerHari) * $selisihHari;
            }
        }

        if ($biayaPenalti > 0) {
            $rental->update([
                'penalty' => $biayaPenalti,
                'status' => 'Pending Denda',
            ]);

            return redirect()->back()->with('success', 'Terlambat ' . $selisihHari . ' hari. Silakan bayar denda terlebih dahulu.');
        } else {
            $rental->update([
                'status' => 'Menunggu Verifikasi',
            ]);

            return redirect()->back()->with('success', 'Pengembalian berhasil, menunggu konfirmasi admin.');
        }
    }

    public function PembayaranDenda($id)
    {
        $rental = Rental::with('motor')->findOrFail($id);

        $waktuSekarang = Carbon::now('Asia/Jakarta')->startOfDay();
        $waktuRencanaKembali = Carbon::parse($rental->tanggal_rencana_kembali, 'Asia/Jakarta')->startOfDay();

        $penaltyNominal = 0;

        $selisihHari = $waktuRencanaKembali->diffInDays($waktuSekarang, false);

        if ($rental->status === 'Pending Denda') {
            $hariTerlambat = $selisihHari > 0 ? $selisihHari : 1;

            $dendaPerHari = 50000;
            $penaltyNominal = $dendaPerHari * $hariTerlambat;
        }

        if ($rental->status !== 'Pending Denda' || $penaltyNominal <= 0) {
            return redirect()->route('customer.orders')->with('error', 'Transaksi ini tidak memiliki denda.');
        }

        \Midtrans\Config::$serverKey = env('MIDTRANS_SERVER_KEY');
        \Midtrans\Config::$isProduction = filter_var(env('MIDTRANS_IS_PRODUCTION', false), FILTER_VALIDATE_BOOLEAN);
        \Midtrans\Config::$isSanitized = true;
        \Midtrans\Config::$is3ds = true;

        $params = [
            'transaction_details' => [
                'order_id' => 'DENDA-' . $rental->kode_booking . '-' . time(),
                'gross_amount' => (int) $penaltyNominal,
            ],
            'customer_details' => [
                'first_name' => Auth::user()->name,
                'email' => Auth::user()->email,
            ],
            'item_details' => [
                [
                    'id' => 'PENALTY-' . $rental->id,
                    'price' => (int) $penaltyNominal,
                    'quantity' => 1,
                    'name' => 'Denda Keterlambatan Sewa ' . $rental->motor->model,
                ]
            ]
        ];

        $snapToken = \Midtrans\Snap::getSnapToken($params);

        return view('payment', [
            'rental'    => $rental,
            'id'        => $rental->id,
            'order'     => $rental,
            'penalty'   => $penaltyNominal,
            'snapToken' => $snapToken,
            'isDenda'   => true
        ]);
    }

    public function payCash(Rental $rental)
    {
        $rental->update([
            'status' => 'Menunggu'
        ]);

        $tokenFonnte = 'vC36PX9CRHcWUgffxgtz';
        $nomorWAAdmin = '082146724109';

        $pesan = "💵 *KUDA BESI RENT - BOOKING METODE CASH* 💵\n\n";
        $pesan .= "Halo Admin, pelanggan berikut memilih metode *Bayar Cash di Tempat*:\n\n";
        $pesan .= "🎟️ *Kode Booking:* " . $rental->kode_booking . "\n";
        $pesan .= "👤 *Penyewa:* " . $rental->user->name . "\n";
        $pesan .= "🏍️ *Unit Motor:* " . $rental->motor->brand . " " . $rental->motor->model . "\n";
        $pesan .= "💰 *Total Tagihan:* Rp " . number_format($rental->total_harga, 0, ',', '.') . "\n\n";
        $pesan .= "Mohon siapkan unit motor dan tunggu kedatangan penyewa untuk transaksi fisik. Terima kasih!";

        try {
            Http::withHeaders([
                'Authorization' => $tokenFonnte,
            ])->asForm()->post('https://api.fonnte.com/send', [
                'target' => $nomorWAAdmin,
                'message' => $pesan,
            ]);
        } catch (\Exception $e) {
        }

        return redirect()->route('customer.orders')->with('success', 'Metode Pembayaran Cash berhasil dipilih! Silakan datang ke garasi sesuai waktu ambil.');
    }

    public function paymentSuccess($id)
    {
        $rental = Rental::where('user_id', Auth::id())->findOrFail($id);

        $rental->update(['status' => 'Disewa']);

        Rental::where('motor_id', $rental->motor_id)
            ->where('status', 'Menunggu')
            ->where('id', '!=', $rental->id)
            ->where(function ($query) use ($rental) {
                $query->whereBetween('tanggal_mulai', [$rental->tanggal_mulai, $rental->tanggal_rencana_kembali])
                    ->orWhereBetween('tanggal_rencana_kembali', [$rental->tanggal_mulai, $rental->tanggal_rencana_kembali]);
            })
            ->update(['status' => 'Gagal']); //

        return view('payment-success', compact('rental'));
    }

    public function paymentFailed($id)
    {
        $rental = Rental::where('user_id', Auth::id())->findOrFail($id);

        return view('payment-failed', compact('rental'));
    }
}
