<?php

namespace App\Http\Controllers;

use App\Models\Motor;
use App\Models\Rental;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RentalController extends Controller
{
    public function checkout(Motor $motor)
    {
        
        if ($motor->status !== 'tersedia') {
            return redirect()->back()->with('error', 'Motor ini sedang tidak tersedia.');
        }

        return view('customer.rentals.checkout', compact('motor'));
    }

    public function store(Request $request)
    {
        if (!Auth::user()->is_verified) {
            return redirect()->route('profile.edit')
                ->with('error', 'Silakan lengkapi profil dan tunggu verifikasi KTP/SIM sebelum menyewa.');
        }

        $user = Auth::user();
        $activeRentalsCount = Rental::where('user_id', $user->id)
            ->whereIn('status', ['dipesan', 'disewa'])
            ->count();

        if ($activeRentalsCount >= $user->rental_limit) {
            return redirect()->back()
                ->with('error', "Batas sewa tercapai. Kamu hanya diperbolehkan menyewa maksimal {$user->rental_limit} motor secara bersamaan.");
        }

        $request->validate([
            'motor_id' => 'required|exists:motors,id',
            'tanggal_mulai' => 'required|date|after_or_equal:today',
            'tanggal_rencana_kembali' => 'required|date|after:tanggal_mulai',
            'payment_proof' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $motor = Motor::findOrFail($request->motor_id);

        $isAvailable = Motor::where('id', $request->motor_id)
            ->where('status', 'tersedia')
            ->exists();

        if (!$isAvailable) {
            return redirect()->back()->with('error', 'Maaf, motor baru saja dipesan orang lain!');
        }

        $start = Carbon::parse($request->tanggal_mulai);
        $end = Carbon::parse($request->tanggal_rencana_kembali);
        $durasi = $start->diffInDays($end) ?: 1;
        $totalHarga = $durasi * $motor->harga_per_hari;

        $path = null;
        if ($request->hasFile('payment_proof')) {
            $path = $request->file('payment_proof')->store('payment-proofs', 'public');
        }

        $rental = Rental::create([
            'user_id' => Auth::id(),
            'motor_id' => $motor->id,
            'tanggal_mulai' => $request->tanggal_mulai,
            'tanggal_rencana_kembali' => $request->tanggal_rencana_kembali,
            'total_harga' => $totalHarga,
            'penalty' => 0, 
            'status' => 'dipesan',
            'payment_proof' => $path,
        ]);

        return redirect()->route('customer.orders')
            ->with('success', "Booking motor {$rental->motor->model} berhasil dengan kode: #{$rental->id}");
    }
}
