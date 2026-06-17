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
