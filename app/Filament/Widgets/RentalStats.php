<?php

namespace App\Filament\Widgets;

use App\Models\Motor;
use App\Models\Rental;
use Carbon\Carbon;
use Filament\Forms\Components\Select;
use Filament\Pages\Dashboard\Concerns\HasFiltersForm;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class RentalStats extends StatsOverviewWidget
{
    use InteractsWithPageFilters;

    protected function getStats(): array
    {
        $periode = $this->filters['filter_waktu'] ?? 'semua';

        $pendapatanQuery = Rental::where('status', 'selesai');
        $deskripsiPendapatan = 'Dari semua transaksi selesai';

        switch ($periode) {
            case 'hari':
                $pendapatanQuery->whereDate('created_at', Carbon::today());
                $deskripsiPendapatan = 'Pendapatan khusus hari ini';
                break;

            case 'minggu':
                $pendapatanQuery->whereBetween('created_at', [
                    Carbon::now()->startOfWeek(),
                    Carbon::now()->endOfWeek()
                ]);
                $deskripsiPendapatan = 'Pendapatan minggu ini (Senin - Minggu)';
                break;

            case 'bulan':
                $pendapatanQuery->whereMonth('created_at', Carbon::now()->month)
                    ->whereYear('created_at', Carbon::now()->year);
                $deskripsiPendapatan = 'Pendapatan bulan ' . Carbon::now()->translatedFormat('F');
                break;

            case 'tahun':
                $pendapatanQuery->whereYear('created_at', Carbon::now()->year);
                $deskripsiPendapatan = 'Total pendapatan tahun ' . Carbon::now()->year;
                break;
        }

        $totalHargaSewa = $pendapatanQuery->clone()->sum('total_harga');

        $totalPenalty = $pendapatanQuery->clone()->sum('penalty');

        $totalPendapatan = $totalHargaSewa + $totalPenalty;

        return [
            Stat::make('Total Pendapatan', 'Rp ' . number_format($totalPendapatan, 0, ',', '.'))
                ->description($deskripsiPendapatan)
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('success'),

            Stat::make('Motor Tersedia', Motor::where('status', 'Tersedia')->count())
                ->description('Motor siap disewakan')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('info'),

            Stat::make('Rental Aktif', Rental::where('status', 'Disewa')->count())
                ->description('Transaksi yang sedang berjalan')
                ->descriptionIcon('heroicon-m-arrow-path')
                ->color('warning'),
        ];
    }
}
