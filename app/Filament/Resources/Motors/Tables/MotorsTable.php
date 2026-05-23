<?php

namespace App\Filament\Resources\Motors\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class MotorsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('category_id')
                    ->numeric()
                    ->sortable(),
                ImageColumn::make('image')
                    ->disk('public')
                    ->width(100)
                    ->height(100),
                TextColumn::make('brand')
                    ->searchable(),
                TextColumn::make('model')
                    ->searchable(),
                TextColumn::make('plate_nomor')
                    ->searchable(),
                TextColumn::make('harga_per_hari')
                    ->money('idr')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('status')
                    ->badge()
                    ->color(
                        fn(string $state): string => match ($state) {
                            'tersedia' => 'success',
                            'perawatan' => 'danger',
                            'dipesan' => 'warning',
                            default => 'info',
                        }
                    )
                    ->icon(fn(string $state): string => match ($state) {
                        'tersedia' => 'heroicon-m-check-circle',
                        'perawatan' => 'heroicon-m-wrench',
                        'dipesan' => 'heroicon-m-no-symbol',
                        default => 'heroicon-m-question-mark-circle',
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
                TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
                RestoreAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
    }
}
