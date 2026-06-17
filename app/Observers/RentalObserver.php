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

                case 'Menunggu Verifikasi':
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
        if ($rental->status === 'Menunggu Verifikasi' || $rental->status === 'Selesai') {
            return;
        }

        $motor = $rental->motor;

        if ($motor && $rental->tanggal_mulai && $rental->tanggal_rencana_kembali) {
            $start = Carbon::parse($rental->tanggal_mulai);
            $rencana = Carbon::parse($rental->tanggal_rencana_kembali);

            $durasiSewa = $start->diffInDays($rencana) ?: 1;
            $biayaSewa = $durasiSewa * $motor->harga_per_hari;

            $hargaPerlengkapan = 0;
            if ($rental->relationLoaded('perlengkapan') || isset($rental->perlengkapan)) {
                $hargaPerlengkapan = $rental->perlengkapan->sum('harga_per_hari') * $durasiSewa;
            }

            $penalty = 0;
            if ($rental->tanggal_pengembalian) {
                $aktual = Carbon::parse($rental->tanggal_pengembalian);
                if ($aktual->greaterThan($rencana)) {
                    $hariTerlambat = $rencana->diffInDays($aktual);
                    $penalty = $hariTerlambat * 50000;
                }
            }

            $rental->penalty = $penalty;

            $rental->total_harga = $biayaSewa + $hargaPerlengkapan + $penalty;
        }
    }
}
