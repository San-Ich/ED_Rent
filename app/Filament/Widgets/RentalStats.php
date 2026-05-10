<?php

namespace App\Filament\Widgets;

use App\Models\Motor;
use App\Models\Rental;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class RentalStats extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Pendapatan', 'Rp ' . number_format(Rental::where('status', 'selesai')->sum('total_harga'), 0, ',', '.'))
                ->description('Dari semua transaksi selesai')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('success'),

            Stat::make('Motor Tersedia', Motor::where('status', 'tersedia')->count())
                ->description('Motor siap disewakan')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('info'),

            Stat::make('Rental Aktif', Rental::where('status', 'dipesan')->count())
                ->description('Transaksi yang sedang berjalan')
                ->descriptionIcon('heroicon-m-arrow-path')
                ->color('warning'),
        ];
    }
}
