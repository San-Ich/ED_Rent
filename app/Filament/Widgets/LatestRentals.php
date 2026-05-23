<?php

namespace App\Filament\Widgets;

use App\Models\Rental;
use Carbon\Carbon;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;

class LatestRentals extends BaseWidget
{
    protected static ?int $sort = 2;
    protected int | string | array $columnSpan = 'full';

    // Memberikan judul yang jelas pada widget
    protected function getTableHeading(): string
    {
        return 'Riwayat Rental (7 Hari Terakhir)';
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Rental::query()
                    ->where('created_at', '>=', Carbon::now()->subDays(7))
                    ->latest()
            )
            ->columns([
                TextColumn::make('created_at')
                    ->label('Waktu Order')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
                TextColumn::make('user.name')
                    ->label('Pelanggan')
                    ->searchable(),
                TextColumn::make('motor.model')
                    ->label('Unit Motor')
                    ->description(fn(Rental $record): string => $record->motor->plate_nomor),
                TextColumn::make('tanggal_mulai')
                    ->label('Mulai')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
                TextColumn::make('tanggal_pengembalian')
                    ->label('Selesai')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'dipesan' => 'warning',
                        'disewa' => 'success',
                        'selesai' => 'info',
                        'dibatalkan' => 'danger',
                        default => 'gray'
                    }),
                TextColumn::make('total_harga')
                    ->label('Total Bayar')
                    ->money('idr'),
            ]);
    }
}
