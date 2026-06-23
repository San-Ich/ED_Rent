<?php

namespace App\Observers;

use App\Filament\Resources\Rentals\Schemas\RentalForm;
use App\Models\Rental;
use Carbon\Carbon;
use Filament\Schemas\Components\Form;
use Illuminate\Support\Facades\DB;

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
        if (in_array($rental->status, ['Pending Denda', 'Selesai', 'Menunggu Verifikasi'])) {
            return;
        }

        if (!$rental->exists) {
            return;
        }

        $motor = $rental->motor;

        if ($motor && $rental->tanggal_mulai && $rental->tanggal_rencana_kembali) {
            $start = Carbon::parse($rental->tanggal_mulai);
            $rencana = Carbon::parse($rental->tanggal_rencana_kembali);

            $durasiSewa = $start->diffInDays($rencana) ?: 1;
            $biayaSewa = $durasiSewa * $motor->harga_per_hari;

            $hargaPerlengkapan = 0;
            $perlengkapanData = $rental->perlengkapan;

            if (($perlengkapanData === null || $perlengkapanData->isEmpty()) && $rental->id) {
                $perlengkapanData = $rental->perlengkapan()->get();
            }

            if ($perlengkapanData && $perlengkapanData->count() > 0) {
                foreach ($perlengkapanData as $item) {
                    $hargaItem = $item->harga_per_hari ?? $item->harga ?? 0;
                    $qty = $item->pivot->jumlah ?? 1;
                    $hargaPerlengkapan += ($hargaItem * $qty);
                }
            }
            $totalBiayaPerlengkapan = $hargaPerlengkapan * $durasiSewa;

            $penalty = 0;
            if ($rental->tanggal_pengembalian) {
                $aktual = Carbon::parse($rental->tanggal_pengembalian);

                if ($aktual->greaterThan($rencana)) {
                    $hariTerlambat = $rencana->startOfDay()->diffInDays($aktual->startOfDay());

                    if ($hariTerlambat > 0) {
                        $penalty = $hariTerlambat * 50000;
                    }
                }
            }

            $rental->penalty = $penalty;
            $rental->total_harga = $biayaSewa + $totalBiayaPerlengkapan + $penalty;
        }
    }
}
