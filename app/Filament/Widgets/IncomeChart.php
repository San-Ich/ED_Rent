<?php

namespace App\Filament\Widgets;

use App\Models\Rental;
use Filament\Widgets\ChartWidget;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;

class IncomeChart extends ChartWidget
{
    protected ?string $heading = 'Tren Pendapatan (2026)';
    protected string $color = 'success';
    protected static ?int $sort = 1;
    protected int | string | array $columnSpan = 'full';
    protected function getData(): array
    {
        $data = Trend::model(Rental::class)
            ->between(
                start: now()->startOfYear(),
                end: now()->endOfYear(),
            )
            ->perMonth()
            ->sum('total_harga');

        return [
            'datasets' => [
                [
                    'label' => 'Pendapatan (Rp)',
                    'data' => $data->map(fn(TrendValue $value) => $value->aggregate),
                ],
            ],
            'labels' => $data->map(fn(TrendValue $value) => $value->date),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
