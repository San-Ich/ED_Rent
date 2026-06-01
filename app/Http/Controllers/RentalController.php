<?php

namespace App\Http\Controllers;

use App\Models\Motor;
use App\Models\Rental;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
        if ($motor->status !== 'tersedia') {
            return redirect()->back()->with('error', 'Motor ini sedang tidak tersedia.');
        }

        return view('customer.rentals.checkout', compact('motor'));
    }

    public function store(Request $request)
    {
        // Validasi Akun Terverifikasi
        // if (!Auth::user()->is_verified) {
        //     return redirect()->route('profile.edit')
        //         ->with('error', 'Silakan lengkapi profil dan tunggu verifikasi KTP/SIM sebelum menyewa.');
        // }

        // Batasan Limit Sewa Akun
        $user = Auth::user();
        $activeRentalsCount = Rental::where('user_id', $user->id)
            ->whereIn('status', ['Menunggu', 'Disewa'])
            ->count();

        if ($activeRentalsCount >= $user->rental_limit) {
            return redirect()->back()
                ->with('error', "Batas sewa tercapai. Kamu hanya diperbolehkan menyewa maksimal {$user->rental_limit} motor secara bersamaan.");
        }

        $request->validate([
            'motor_id'                => 'required|exists:motors,id',
            'tanggal_mulai'           => 'required',
            'tanggal_rencana_kembali' => 'required',
        ]);

        $motor = Motor::findOrFail($request->motor_id);

        $isAvailable = Motor::where('id', $request->motor_id)
            ->where('status', 'Tersedia')
            ->exists();

        if (!$isAvailable) {
            return redirect()->back()->with('error', 'Maaf, motor baru saja dipesan orang lain!');
        }

        $tglMulai   = str_replace('T', ' ', $request->tanggal_mulai);
        $tglKembali = str_replace('T', ' ', $request->tanggal_rencana_kembali);

        $start  = Carbon::parse($tglMulai);
        $end    = Carbon::parse($tglKembali);
        $durasi = $start->diffInDays($end) ?: 1;

        $totalHarga = $durasi * $motor->harga_per_hari;

        if ($request->metode_pengantaran === 'delivery') {
            $totalHarga += 75000;
        }

        $kodeBooking = 'KBB-' . date('Ymd') . '-' . strtoupper(\Illuminate\Support\Str::random(5));

        // SIMPAN DATA KE DATABASE
        $rental = Rental::create([
            'user_id'                 => Auth::id(),
            'motor_id'                => $motor->id,
            'kode_booking'            => $kodeBooking,
            'tanggal_mulai'           => $tglMulai,
            'tanggal_rencana_kembali' => $tglKembali,
            'total_harga'             => $totalHarga,
            'penalty'                 => 0,
            'status'                  => 'Menunggu',
            'payment_proof'           => null,
            // 'alamat_pengantaran'   => $request->alamat_pengantaran,
        ]);

        //
        return redirect()->route('customer.orders')
            ->with('success', "Booking motor {$rental->motor->model} berhasil! Silakan cek daftarnya di sini.");
    }

    public function paymentPage($id)
    {
        $rental = Rental::with(['motor', 'user'])->where('user_id', Auth::id())->findOrFail($id);

        if ($rental->status !== 'Menunggu' && $rental->status !== 'waiting') {
            return redirect()->route('customer.orders')->with('error', 'Transaksi ini tidak membutuhkan pembayaran.');
        }

        // Konfigurasi Midtrans
        Config::$serverKey = env('MIDTRANS_SERVER_KEY');
        Config::$isProduction = env('MIDTRANS_IS_PRODUCTION') === 'true';
        Config::$isSanitized = true;
        Config::$is3ds = true;

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
            ]
        ];

        $snapToken = Snap::getSnapToken($params);

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

        if ($signatureKey !== $localSignature) {
            return response()->json(['message' => 'Invalid Signature'], 403);
        }

        // Cek apakah ini transaksi pembayaran denda atau sewa biasa
        $isDendaPayment = str_starts_with($orderId, 'DENDA-');

        if ($isDendaPayment) {
            $cleanOrderId = str_replace('DENDA-', '', $orderId);
            $actualOrderId = substr($cleanOrderId, 0, strrpos($cleanOrderId, '-'));
        } else {
            $actualOrderId = substr($orderId, 0, strrpos($orderId, '-'));
        }

        $rental = Rental::where('kode_booking', $actualOrderId)->first();

        if (!$rental) {
            return response()->json(['message' => 'Transaksi tidak ditemukan'], 404);
        }

        if ($transactionStatus == 'capture' || $transactionStatus == 'settlement') {

            if ($isDendaPayment) {
                $rental->update(['status' => 'Selesai']);

                if ($rental->motor_id) {
                    Motor::where('id', $rental->motor_id)->update(['status' => 'Tersedia']);
                }
            } else {
                $rental->update(['status' => 'Disewa']);

                if ($rental->motor_id) {
                    Motor::where('id', $rental->motor_id)->update(['status' => 'Disewa']);
                }
            }
        } elseif ($transactionStatus == 'pending') {

            if (!$isDendaPayment) {
                $rental->update(['status' => 'Menunggu']);
            }
        } elseif (in_array($transactionStatus, ['deny', 'expire', 'cancel'])) {

            if ($isDendaPayment) {
                $rental->update(['status' => 'Pending Denda']);
            } else {
                $rental->update(['status' => 'Gagal']);

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

        $penaltyNominal = max(0, (int) $rental->total_harga);

        if ($waktuSekarang->gt($waktuRencanaKembali)) {
            $selisihHari = abs($waktuRencanaKembali->diffInDays($waktuSekarang));

            if ($selisihHari > 0) {
                $dendaPerHari = 50000;

                $totalDendaHitung = ($rental->motor->harga_per_hari + $dendaPerHari) * $selisihHari;

                $penaltyNominal = max(0, $totalDendaHitung);
            }
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
}
