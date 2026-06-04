<?php

namespace App\Filament\Resources\Users\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class UsersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('email')
                    ->label('Email Address')
                    ->searchable(),

                TextColumn::make('phone')
                    ->label('No. WhatsApp')
                    ->searchable(),

                TextColumn::make('role')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'admin' => 'danger',
                        'user' => 'success',
                    }),

                TextColumn::make('rental_limit')
                    ->label('Limit Sewa (Hari)')
                    ->sortable()
                    ->alignCenter(),

            ImageColumn::make('ktp_path')
                ->label('Foto KTP')
                ->disk('public')
                ->square()
                ->size(50)
                ->placeholder('Belum Upload')
                ->url(fn($record) => $record->ktp_path ? asset('storage/' . $record->ktp_path) : null)
                ->openUrlInNewTab(),

            ImageColumn::make('sim_path')
                ->label('Foto SIM')
                ->disk('public')
                ->square()
                ->size(50)
                ->placeholder('Belum Upload')
                ->url(fn($record) => $record->sim_path ? asset('storage/' . $record->sim_path) : null)
                ->openUrlInNewTab(),

                IconColumn::make('is_verified')
                    ->label('Status Verifikasi')
                    ->boolean()
                    ->sortable()
                    ->alignCenter(),

                TextColumn::make('address')
                    ->label('Alamat')
                    ->limit(30)
                    ->searchable(),

                TextColumn::make('email_verified_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
            ]);
    }
}
