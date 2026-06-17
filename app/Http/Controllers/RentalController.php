<?php

namespace App\Http\Controllers;

use App\Models\Motor;
use App\Models\Perlengkapan;
use App\Models\Rental;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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
    
        $rental = Rental::with(['motor', 'user', 'perlengkapan'])->where('user_id', Auth::id())->findOrFail($id);
        $waktuSekarang = Carbon::now('Asia/Jakarta');


        $isDenda = ($rental->status === 'Pending Denda');

        if (!$isDenda) {
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
                })->exists();

            if ($isMotorTaken) {
                $rental->update(['status' => 'Gagal']);
                return redirect()->route('payment.failed', $rental->id)
                    ->with('error', 'Maaf, unit motor ini baru saja didahului oleh penyewa lain yang membayar lebih cepat!');
            }
        }

        \Midtrans\Config::$serverKey = env('MIDTRANS_SERVER_KEY');
        \Midtrans\Config::$isProduction = filter_var(env('MIDTRANS_IS_PRODUCTION', false), FILTER_VALIDATE_BOOLEAN);
        \Midtrans\Config::$isSanitized = true;
        \Midtrans\Config::$is3ds = true;

        $duration = 15;
        $unit = 'minute';

        if ($isDenda) {
            $waktuRencanaKembali = Carbon::parse($rental->tanggal_rencana_kembali, 'Asia/Jakarta')->startOfDay();
            $selisihHari = $waktuRencanaKembali->diffInDays($waktuSekarang->copy()->startOfDay(), false);
            $hariTerlambat = $selisihHari > 0 ? $selisihHari : 1;

            $dendaPerHari = 50000;
            $penaltyNominal = $dendaPerHari * $hariTerlambat;

            if ($penaltyNominal <= 0) {
                return redirect()->route('customer.orders')->with('error', 'Transaksi ini tidak memiliki nominal denda valid.');
            }

            if ($rental->denda_snap_token && $rental->denda_expired_at && $waktuSekarang->lessThan($rental->denda_expired_at)) {
                $snapToken = $rental->denda_snap_token;
            } else {
            
                $orderIdDenda = 'DENDA-' . $rental->kode_booking;

                $params = [
                    'transaction_details' => [
                        'order_id' => $orderIdDenda,
                        'gross_amount' => (int) $penaltyNominal,
                    ],
                    'customer_details' => [
                        'first_name' => $rental->user->name,
                        'email' => $rental->user->email,
                        'phone' => $rental->user->phone ?? '',
                    ],
                    'item_details' => [
                        [
                            'id' => 'PENALTY-' . $rental->id,
                            'price' => (int) $penaltyNominal,
                            'quantity' => 1,
                            'name' => 'Denda Keterlambatan ' . $rental->motor->brand . ' ' . $rental->motor->model,
                        ]
                    ],
                    'expiry' => [
                        'start_time' => date("Y-m-d H:i:s O"),
                        'duration' => $duration,
                        'unit' => $unit
                    ]
                ];

                $snapToken = \Midtrans\Snap::getSnapToken($params);

                $rental->denda_snap_token = $snapToken;
                $rental->denda_expired_at = Carbon::now()->addMinutes($duration);
                $rental->save();
            }
        } else {
            $penaltyNominal = 0;

            if ($rental->snap_token && $rental->payment_expired_at && $waktuSekarang->lessThan($rental->payment_expired_at)) {
                $snapToken = $rental->snap_token;
            } else {
                $params = [
                    'transaction_details' => [
                        'order_id' => $rental->kode_booking,
                        'gross_amount' => (int) $rental->total_harga,
                    ],
                    'customer_details' => [
                        'first_name' => $rental->user->name,
                        'email' => $rental->user->email,
                        'phone' => $rental->user->phone ?? '',
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
                    ],
                    'callbacks' => [
                        'finish' => route('payment.success', $rental->id),
                        'unfinish' => route('customer.orders'),
                        'error' => route('payment.failed', $rental->id)
                    ]
                ];

                $snapToken = \Midtrans\Snap::getSnapToken($params);

                $rental->snap_token = $snapToken;
                $rental->payment_expired_at = Carbon::now()->addMinutes($duration);
                $rental->save();
            }
        }

        return view('payment', [
            'rental'    => $rental,
            'id'        => $rental->id,
            'order'     => $rental,
            'penalty'   => $penaltyNominal,
            'snapToken' => $snapToken,
            'isDenda'   => $isDenda
        ]);
    }

    public function midtransWebhook(Request $request)
    {
        $payload = $request->all();

        $orderId           = $payload['order_id'];
        $statusCode        = $payload['status_code'];
        $grossAmount       = $payload['gross_amount'];
        $transactionStatus = $payload['transaction_status'];
        $signatureKey      = $payload['signature_key'];

        $serverKey      = env('MIDTRANS_SERVER_KEY');
        $localSignature = hash("sha512", $orderId . $statusCode . $grossAmount . $serverKey);

        if ($signatureKey !== $localSignature) {
            Log::error('Webhook Gagal: Invalid Signature untuk Order ID ' . $orderId);
            return response()->json(['message' => 'Invalid Signature'], 403);
        }

        $statusMurni = strtolower($transactionStatus);

        // Log::info("========================================");
        // Log::info("HASIL DD PAYLOAD MIDTRANS:", $payload);
        // Log::info("APAKAH DENDA? : " . (str_starts_with($orderId, 'DENDA-') ? 'YA' : 'TIDAK'));
        // Log::info("KODE BOOKING HASIL POTONG: " . str_replace(['DENDA-', '-RETRY'], '', $orderId));
        // Log::info("========================================");
        // Log::info("Midtrans Webhook Masuk. Order ID: {$orderId} | Status: {$statusMurni}");

        $isDendaPayment = str_starts_with($orderId, 'DENDA-');
        $actualOrderId  = str_replace(['DENDA-', '-RETRY'], '', $orderId);

        $rental = Rental::where('kode_booking', $actualOrderId)->first();

        if (!$rental) {
            Log::error('Webhook Gagal: Data Rental tidak ditemukan untuk Kode Booking: ' . $actualOrderId);
            return response()->json(['message' => 'Data rental tidak ditemukan'], 404);
        }

        if ($statusMurni == 'capture' || $statusMurni == 'settlement') {

            if ($isDendaPayment) {

                $nominalDenda   = (int) $grossAmount;
                $totalHargaBaru = (int) $rental->total_harga + $nominalDenda;

                DB::table('rentals')->where('id', $rental->id)->update([
                    'status'      => 'Menunggu Verifikasi',
                    'penalty'     => $nominalDenda,
                    'total_harga' => $totalHargaBaru,
                ]);

                if ($rental->motor_id) {
                    DB::table('motors')->where('id', $rental->motor_id)->update(['status' => 'Tersedia']);
                }

                Log::info('Webhook Berhasil: Denda Lunas. Status Rental: Menunggu Verifikasi. ID: ' . $actualOrderId);
            } else {

                DB::table('rentals')->where('id', $rental->id)->update(['status' => 'Disewa']);

                if ($rental->motor_id) {
                    DB::table('motors')->where('id', $rental->motor_id)->update(['status' => 'Disewa']);
                }

                Log::info('Webhook Berhasil: Sewa Utama Active [Disewa] untuk ID: ' . $actualOrderId);
            }
        } elseif ($statusMurni == 'pending') {

            if (!$isDendaPayment && !in_array($rental->status, ['Disewa', 'Menunggu Verifikasi'])) {
                DB::table('rentals')->where('id', $rental->id)->update(['status' => 'Menunggu']);
            }
        } elseif (in_array($statusMurni, ['deny', 'expire', 'cancel'])) {

            if ($isDendaPayment) {
                DB::table('rentals')->where('id', $rental->id)->update(['status' => 'Pending Denda']);
            } else {
                DB::table('rentals')->where('id', $rental->id)->update(['status' => 'Gagal']);
                if ($rental->motor_id) {
                    DB::table('motors')->where('id', $rental->motor_id)->update(['status' => 'Tersedia']);
                }
            }
        } 

        return response()->json([
            'status' => 'Success',
            'message' => 'Database KudaBesiRent berhasil diperbarui!'
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

    public function KonfirmasiMotor(Request $request, $id)
    {
        
        $rental = Rental::with('motor')->findOrFail($id);

        if ($request->opsi_kehadiran === 'titip_cabang') {
            $request->validate([
                'cabang_kembali_id' => 'required',
                'foto_serah_terima_cabang' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
            ], [
                'cabang_kembali_id.required' => 'Anda wajib memilih cabang tempat menitipkan motor.',
                'foto_serah_terima_cabang.required' => 'Bukti foto serah terima kunci wajib diunggah.',
            ]);

            if ($request->hasFile('foto_serah_terima_cabang')) {
                $path = $request->file('foto_serah_terima_cabang')->store('bukti_cabang', 'public');
                $rental->foto_serah_terima_cabang = $path;
            }

            $rental->cabang_kembali_id = $request->cabang_kembali_id;
        }

    
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
            $rental->penalty = $biayaPenalti;
            $rental->status = 'Pending Denda';
            $rental->save();

            return redirect()->back()->with('success', 'Terlambat ' . $selisihHari . ' hari. Silakan bayar denda terlebih dahulu.');
        } else {
            $rental->status = 'Menunggu Verifikasi';
            $rental->save();

            return redirect()->back()->with('success', 'Pengembalian berhasil, menunggu konfirmasi admin.');
        }
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

    public function paymentSuccess(Request $request, $id)
    {
        $rental = Rental::where('user_id', Auth::id())->findOrFail($id);

        $isDenda = $request->query('type') === 'denda';
        $grossAmount = $request->query('gross_amount');

        if ($isDenda) {

            if ($rental->penalty == 0 && $grossAmount) {
                $nominalDenda = (int) $grossAmount;

                $rental->status = 'Menunggu Verifikasi';
                $rental->penalty = $nominalDenda;
                $rental->total_harga = (int) $rental->total_harga + $nominalDenda;
            }
        } else {

            $rental->update(['status' => 'Disewa']);

            Rental::where('motor_id', $rental->motor_id)
                ->where('status', 'Menunggu')
                ->where('id', '!=', $rental->id)
                ->where(function ($query) use ($rental) {
                    $query->whereBetween('tanggal_mulai', [$rental->tanggal_mulai, $rental->tanggal_rencana_kembali])
                        ->orWhereBetween('tanggal_rencana_kembali', [$rental->tanggal_mulai, $rental->tanggal_rencana_kembali]);
                })
                ->update(['status' => 'Gagal']);
        }

        return view('payment-success', compact('rental'));
    }

    public function paymentFailed(Request $request, $id)
    {
        $rental = Rental::where('user_id', Auth::id())->findOrFail($id);

        $isDenda = $request->query('type') === 'denda';

        return view('payment-failed', compact('rental', 'isDenda'));
    }

    public function handleDirectFailed()
    {
        $latestRental = Rental::where('user_id', Auth::id())
            ->latest()
            ->first();

        if ($latestRental) {
            return redirect()->route('payment.failed', $latestRental->id);
        }

        return redirect()->route('customer.orders.index')
            ->with('error', 'Waktu pembayaran online Anda telah habis.');
    }

    public function checkFonnteStatus()
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => env('FONNTE_TOKEN'),
            ])->post('https://api.fonnte.com/device');

            $result = $response->json();

            if ($response->successful() && isset($result['status']) && $result['status'] === true) {
                if (isset($result['device']['status']) && $result['device']['status'] === 'connected') {
                    return true;
                }
            }

            return false;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function destroy($id)
    {
        $order = Rental::findOrFail($id);

        $authUserId = Auth::id();
        if ($order->user_id !== $authUserId) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses untuk menghapus pesanan ini.');
        }

        if (in_array($order->status, ['Disewa', 'active', 'Pending Denda'])) {
            return redirect()->back()->with('error', 'Pesanan yang sedang berjalan atau memiliki denda tidak dapat dihapus.');
        }

        $order->delete();

        return redirect()->back()->with('success', 'Riwayat pesanan berhasil dihapus.');
    }
}
