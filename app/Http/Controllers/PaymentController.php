<?php

namespace App\Http\Controllers;

use App\Models\Rental;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
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
            $waktuSekarangSaja = $waktuSekarang->copy()->setTimezone('Asia/Jakarta')->startOfDay();

            $hariTerlambat = 0;
            if ($waktuSekarangSaja->greaterThan($waktuRencanaKembali)) {
                $hariTerlambat = $waktuRencanaKembali->diffInDays($waktuSekarangSaja);
            }

            if ($hariTerlambat <= 0) {
                $hariTerlambat = 1;
            }

            $dendaPerHari = 50000;
            $penaltyNominal = $dendaPerHari * $hariTerlambat;

            if ($penaltyNominal <= 0) {
                return redirect()->route('customer.orders')->with('error', 'Transaksi ini tidak memiliki nominal denda valid.');
            }

            $orderIdDenda = 'DENDA-' . $rental->kode_booking . '-' . time();

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
                        'name' => 'Denda Terlambat ' . $hariTerlambat . ' Hari - ' . $rental->motor->brand,
                    ]
                ],
                'expiry' => [
                    'start_time' => date("Y-m-d H:i:s O"),
                    'duration' => $duration,
                    'unit' => $unit
                ],
                'callbacks' => [
                    'finish' => route('payment.success', $rental->id) . '?type=denda&gross_amount=' . $penaltyNominal,
                    'unfinish' => route('customer.orders'),
                    'error' => route('payment.failed', $rental->id)
                ]
            ];

            $snapToken = \Midtrans\Snap::getSnapToken($params);

            $rental->denda_snap_token = $snapToken;
            $rental->penalty = $penaltyNominal;
            $rental->denda_expired_at = Carbon::now()->addMinutes($duration);

            DB::table('rentals')->where('id', $rental->id)->update([
                'denda_snap_token' => $snapToken,
                'penalty' => $penaltyNominal,
                'denda_expired_at' => Carbon::now()->addMinutes($duration)
            ]);
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
                        'finish' => route('payment.success', $rental->id) . '?type=sewa&gross_amount=' . $rental->total_harga,
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

        $isDendaPayment = str_starts_with($orderId, 'DENDA-');
        $actualOrderId  = str_replace(['DENDA-', '-RETRY'], '', $orderId);

        $rental = Rental::with(['motor', 'perlengkapan'])->where('kode_booking', $actualOrderId)->first();

        if (!$rental) {
            Log::error('Webhook Gagal: Data Rental tidak ditemukan untuk Kode Booking: ' . $actualOrderId);
            return response()->json(['message' => 'Data rental tidak ditemukan'], 404);
        }

        if ($statusMurni == 'capture' || $statusMurni == 'settlement') {

            if ($isDendaPayment) {
                $nominalDenda = (int) $grossAmount;

                $start = \Carbon\Carbon::parse($rental->tanggal_mulai);
                $rencana = \Carbon\Carbon::parse($rental->tanggal_rencana_kembali);
                $durasiSewa = $start->diffInDays($rencana) ?: 1;

                $biayaSewaAsli = $durasiSewa * $rental->motor->harga_per_hari;

                $biayaPerlengkapan = 0;
                if ($rental->perlengkapan && $rental->perlengkapan->count() > 0) {
                    foreach ($rental->perlengkapan as $item) {
                        $hargaItem = $item->harga_per_hari ?? $item->harga ?? 0;
                        $qty = $item->pivot->jumlah ?? 1;
                        $biayaPerlengkapan += ($hargaItem * $qty);
                    }
                }
                $totalBiayaPerlengkapan = $biayaPerlengkapan * $durasiSewa;

                $totalHargaPas = $biayaSewaAsli + $totalBiayaPerlengkapan + $nominalDenda;

                DB::table('rentals')->where('id', $rental->id)->update([
                    'status'      => 'Menunggu Verifikasi',
                    'penalty'     => $nominalDenda,
                    'total_harga' => $totalHargaPas,
                ]);

                if ($rental->motor_id) {
                    DB::table('motors')->where('id', $rental->motor_id)->update(['status' => 'Tersedia']);
                }

                Log::info('Webhook Berhasil: Mengunci total harga denda mutlak.');
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
            if (!$rental->relationLoaded('perlengkapan')) {
                $rental->load('perlengkapan');
            }

            $start = \Carbon\Carbon::parse($rental->tanggal_mulai);
            $rencana = \Carbon\Carbon::parse($rental->tanggal_rencana_kembali);
            $durasiSewa = $start->diffInDays($rencana) ?: 1;

            $biayaSewaAsli = $durasiSewa * $rental->motor->harga_per_hari;

            $biayaPerlengkapan = 0;
            if ($rental->perlengkapan && $rental->perlengkapan->count() > 0) {
                foreach ($rental->perlengkapan as $item) {
                    $hargaItem = $item->harga_per_hari ?? $item->harga ?? 0;
                    $qty = $item->pivot->jumlah ?? 1;
                    $biayaPerlengkapan += ($hargaItem * $qty);
                }
            }
            $totalBiayaPerlengkapan = $biayaPerlengkapan * $durasiSewa;

            $nominalDenda = $grossAmount ? (int)$grossAmount : (int)$rental->penalty;

            $totalHargaPas = $biayaSewaAsli + $totalBiayaPerlengkapan + $nominalDenda;

            DB::table('rentals')->where('id', $rental->id)->update([
                'status'      => 'Menunggu Verifikasi',
                'penalty'     => $nominalDenda,
                'total_harga' => $totalHargaPas,
            ]);

            if ($rental->motor_id) {
                DB::table('motors')->where('id', $rental->motor_id)->update(['status' => 'Tersedia']);
            }

            $rental = Rental::with(['motor', 'user', 'perlengkapan'])->findOrFail($id);
        } else {
            DB::table('rentals')->where('id', $rental->id)->update([
                'status' => 'Disewa'
            ]);

            if ($rental->motor_id) {
                DB::table('motors')->where('id', $rental->motor_id)->update(['status' => 'Disewa']);
            }

            Rental::where('motor_id', $rental->motor_id)
                ->where('status', 'Menunggu')
                ->where('id', '!=', $rental->id)
                ->where(function ($query) use ($rental) {
                    $query->whereBetween('tanggal_mulai', [$rental->tanggal_mulai, $rental->tanggal_rencana_kembali])
                        ->orWhereBetween('tanggal_rencana_kembali', [$rental->tanggal_mulai, $rental->tanggal_rencana_kembali]);
                })
                ->update(['status' => 'Gagal']);

            $rental = $rental->refresh();
        }

        return view('payment-success', compact('rental'));
    }

    public function paymentFailed(Request $request, $id)
    {
        $rental = Rental::where('user_id', Auth::id())->findOrFail($id);

        $isDenda = $request->query('type') === 'denda';

        return view('payment-failed', compact('rental', 'isDenda'));
    }
}
