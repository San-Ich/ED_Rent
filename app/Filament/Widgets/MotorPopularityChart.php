<?php

namespace App\Filament\Widgets;

use App\Models\Rental;
use App\Models\Motor;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class MotorPopularityChart extends ChartWidget
{
    protected ?string $heading = 'Motor Paling Laris (Frekuensi Sewa)';
    protected static ?int $sort = 1;
    protected int | string | array $columnSpan = 1;
    protected string $color = 'info';

    protected function getData(): array
    {
        // Query untuk mengambil nama motor dan jumlah sewanya
        $data = Rental::select('motor_id', DB::raw('count(*) as total'))
            ->groupBy('motor_id')
            ->orderBy('total', 'desc')
            ->limit(5) // Kita ambil top 5 saja agar grafik tidak sesak
            ->get();

        // Mengambil label (nama model motor) berdasarkan motor_id
        $labels = $data->map(function ($item) {
            return Motor::find($item->motor_id)?->model ?? 'Unknown';
        });

        // Mengambil nilai (jumlah sewa)
        $counts = $data->map(fn($item) => $item->total);

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Kali Disewa',
                    'data' => $counts,
                    'backgroundColor' => [
                        '#36A2EB',
                        '#FF6384',
                        '#FFCE56',
                        '#4BC0C0',
                        '#9966FF'
                    ],
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
