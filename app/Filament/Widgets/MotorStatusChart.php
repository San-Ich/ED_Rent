<?php

namespace App\Filament\Widgets;

use App\Models\Motor;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class MotorStatusChart extends ChartWidget
{
    protected ?string $heading = 'Status Ketersediaan Motor';

    protected static ?int $sort = 1;
    protected int | string | array $columnSpan = 1;

    protected function getData(): array
    {
        // Menghitung jumlah motor per status
        $data = Motor::select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->get();

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Motor',
                    'data' => $data->pluck('total'),
                    'backgroundColor' => [
                        '#4ade80', // Hijau untuk tersedia
                        '#fbbf24', // Kuning untuk dipesan
                        '#f87171', // Merah untuk dibatalkan/rusak jika ada
                    ],
                ],
            ],
            'labels' => $data->pluck('status')->map(fn($status) => ucfirst($status)),
        ];
    }

    protected function getType(): string
    {
        return 'doughnut'; // Gunakan doughnut agar terlihat lebih modern daripada pie biasa
    }
}
