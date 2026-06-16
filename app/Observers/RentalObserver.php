<?php

namespace App\Observers;

use App\Filament\Resources\Rentals\Schemas\RentalForm;
use App\Models\Rental;
use Carbon\Carbon;
use Filament\Schemas\Components\Form;

class RentalObserver
{
    /**
     * Handle the Rental "created" event.
     */
    public function created(Rental $rental): void
    {
        // $rental->motor->update(['status' => 'Disewa']);
    }

    public function updated(Rental $rental): void
    {
        if ($rental->isDirty('status')) {
            $motor = $rental->motor;

            if (!$motor) {
                return;
            }

            switch ($rental->status) {
                case 'Disewa':
                    $motor->update(['status' => 'Disewa']);
                    break;

                case 'Selesai':
                case 'Gagal':
                case 'Batal':
                    $motor->update(['status' => 'Tersedia']);
                    break;

                case 'Pending Denda':
                    break;
            }
        }
    }

    /**
     * Handle the Rental "deleted" event.
     */
    public function deleted(Rental $rental): void
    {
        $rental->motor->update(['status' => 'Tersedia']);
    }

    /**
     * Handle the Rental "restored" event.
     */
    public function restored(Rental $rental): void
    {
        //
    }

    /**
     * Handle the Rental "force deleted" event.
     */
    public function forceDeleted(Rental $rental): void
    {
        //
    }

    public function saving(Rental $rental): void
    {
        $motor = $rental->motor;

        if ($motor && $rental->tanggal_mulai && $rental->tanggal_rencana_kembali) {
            $start = Carbon::parse($rental->tanggal_mulai);
            $rencana = Carbon::parse($rental->tanggal_rencana_kembali);

            $durasiSewa = $start->diffInDays($rencana) ?: 1;
            $biayaSewa = $durasiSewa * $motor->harga_per_hari;

            $penalty = 0;
            if ($rental->tanggal_pengembalian) {
                $aktual = Carbon::parse($rental->tanggal_pengembalian);
                if ($aktual->greaterThan($rencana)) {
                    $hariTerlambat = $rencana->diffInDays($aktual);
                    $penalty = $hariTerlambat * 50000;
                }
            }

            $rental->penalty = $penalty;

            if ($rental->total_harga > 0) {
                $rental->total_harga = $rental->total_harga + $penalty;
            } else {
                $rental->total_harga = $biayaSewa + $penalty;
            }
        }
    }
}
