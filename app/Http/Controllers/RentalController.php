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
        $request->validate([
            'motor_id' => 'required|exists:motors,id',
            'tanggal_mulai' => 'required|date|after_or_equal:today',
            'tanggal_rencana_kembali' => 'required|date|after:tanggal_mulai',
        ]);

        $motor = Motor::findOrFail($request->motor_id);

        
        $start = Carbon::parse($request->tanggal_mulai);
        $end = Carbon::parse($request->tanggal_rencana_kembali);
        $durasi = $start->diffInDays($end) ?: 1;
        $totalHarga = $durasi * $motor->harga_per_hari;

        $rental = Rental::create([
            'user_id' => Auth::id(),
            'motor_id' => $motor->id,
            'tanggal_mulai' => $request->tanggal_mulai,
            'tanggal_rencana_kembali' => $request->tanggal_rencana_kembali,
            'total_harga' => $totalHarga,
            'penalty' => 0, 
            'status' => 'dipesan',
        ]);

        return redirect()->route('customer.orders')
            ->with('success', "Booking motor {$rental->motor->model} berhasil dengan kode: #{$rental->id}");
    }
}
