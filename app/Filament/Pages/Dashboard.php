<?php

namespace App\Filament\Pages;

use Filament\Pages\Dashboard as BaseDashboard;
use Filament\Forms\Components\Select;
use Filament\Pages\Dashboard\Concerns\HasFiltersForm;

class Dashboard extends BaseDashboard
{
    use HasFiltersForm;

    public function filtersForm($form)
    {
        return $form
            ->schema([
                Select::make('filter_waktu')
                    ->label('Periode Pendapatan')
                    ->options([
                        'hari' => 'Hari Ini',
                        'minggu' => 'Minggu Ini',
                        'bulan' => 'Bulan Ini',
                        'tahun' => 'Tahun Ini',
                        'semua' => 'Semua Transaksi',
                    ])
                    ->default('semua')
                    ->native(false),
            ])
            ->columns(1);
    }

    public function getWidgetsColumns(): int | string | array
    {
        return 1;
    }
}
