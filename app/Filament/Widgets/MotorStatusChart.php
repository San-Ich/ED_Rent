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
        $data = Motor::select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->get();

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Motor',
                    'data' => $data->pluck('total'),
                    'backgroundColor' => [
                        '#32CD32', // Hijau untuk tersedia
                        '#fbbf24', // Kuning untuk dipesan
                        '#FF0000', // Merah untuk perawatan
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
