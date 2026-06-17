<?php

namespace App\Filament\Resources\Rentals\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\TextEntry;

class RentalsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user_id')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('motor_id')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('kode_booking')
                    ->sortable(),
                TextColumn::make('perlengkapan.nama_perlengkapan')
                    ->label('Perlengkapan Tambahan')
                    ->placeholder('Tidak ada tambahan'),
                TextColumn::make('tanggal_mulai')
                    ->date()
                    ->sortable(),
                TextColumn::make('tanggal_rencana_kembali')
                    ->date()
                    ->sortable(),
                TextColumn::make('tanggal_pengembalian')
                    ->date()
                    ->sortable(),
                TextColumn::make('total_harga')
                    ->money('idr')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('penalty')
                    ->money('idr')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'Selesai'  => 'success',
                        'Disewa'   => 'info',
                        'Menunggu' => 'warning',
                        'Gagal'    => 'danger',
                        'Pending Data' => 'danger',
                        'Menunggu Verifikasi' => 'primary',
                        default    => 'gray',
                    })
                    ->icon(fn(string $state): string => match ($state) {
                        'Selesai'  => 'heroicon-m-check-badge',
                        'Disewa'   => 'heroicon-m-arrow-path',
                        'Menunggu' => 'heroicon-m-clock',
                        'Gagal'    => 'heroicon-m-exclamation-circle',
                        'Pending Denda' => 'heroicon-m-exclamation-triangle',
                        'Menunggu Verifikasi' => 'heroicon-m-clipboard-document-check',
                        default    => 'heroicon-m-question-mark-circle',
                    })
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('cabang_kembali_id')
                    ->label('Lokasi Cabang Kembali')
                    ->badge()
                    ->color('warning')
                    ->formatStateUsing(fn($state) => match ($state) {
                        1 => '📍 Cabang Bandara',
                        2 => '📍 Cabang Stasiun',
                        default => 'Garasi Pusat / Menunggu Jemput',
                    })
                    ->sortable(),
                ImageColumn::make('foto_serah_terima_cabang')
                    ->label('Bukti Serah Terima')
                    ->disk('public')
                    ->url(fn($record) => $record->foto_serah_terima_cabang ? asset('storage/' . $record->foto_serah_terima_cabang) : null)
                    ->openUrlInNewTab()
                    ->size(50),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
        ;
    }
}
