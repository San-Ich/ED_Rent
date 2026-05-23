<?php

namespace App\Filament\Widgets;

use App\Models\Rental;
use Filament\Widgets\ChartWidget;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;
use Carbon\Carbon;

class IncomeChart extends ChartWidget
{
    protected ?string $heading = 'Tren Pendapatan (2026)';
    protected string $color = 'success';
    protected static ?int $sort = 1;
    protected int | string | array $columnSpan = 'full';

    public ?string $filter = 'month';

    protected function getFilters(): ?array
    {
        return [
            'week' => 'Minggu Ini',
            'month' => 'Bulan Ini',
            'year' => 'Tahun Ini',
        ];
    }

    protected function getData(): array
    {
        $activeFilter = $this->filter;
        $query = Trend::model(Rental::class);

        switch ($activeFilter) {
            case 'week':
                $start = now()->startOfWeek();
                $end = now()->endOfWeek();
                $data = $query->between(start: $start, end: $end)
                    ->perDay() 
                    ->sum('total_harga');
                $format = 'D'; 
                break;

            case 'year':
                $start = now()->startOfYear();
                $end = now()->endOfYear();
                $data = $query->between(start: $start, end: $end)
                    ->perMonth()
                    ->sum('total_harga');
                $format = 'M'; 
                break;

            case 'month':
            default:
                $start = now()->startOfMonth();
                $end = now()->endOfMonth();
                $data = $query->between(start: $start, end: $end)
                    ->perDay() 
                    ->sum('total_harga');
                $format = 'd M';
                break;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Pendapatan (Rp)',
                    'data' => $data->map(fn(TrendValue $value) => $value->aggregate),
                    'fill' => 'start',
                    'tension' => 0.4,
                ],
            ],
            'labels' => $data->map(fn(TrendValue $value) => Carbon::parse($value->date)->translatedFormat($format)),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
