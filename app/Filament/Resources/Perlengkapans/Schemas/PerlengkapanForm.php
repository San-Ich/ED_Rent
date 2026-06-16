<?php

namespace App\Filament\Resources\Perlengkapans\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class PerlengkapanForm
{
    public static function configure($schema)
    {
        return $schema
            ->columns(3)
            ->components([

                TextInput::make('nama_perlengkapan')
                    ->label('Nama Perlengkapan')
                    ->placeholder('Contoh: Helm SNI Executive, Jas Hujan Ponco, dll.')
                    ->required()
                    ->maxLength(255)
                    ->columnSpan(3),

                TextInput::make('harga_per_hari')
                    ->label('Harga Sewa Per Hari')
                    ->numeric()
                    ->prefix('Rp')
                    ->placeholder('0')
                    ->required()
                    ->columnSpan(2),

                TextInput::make('stok')
                    ->label('Stok Unit')
                    ->numeric()
                    ->default(0)
                    ->placeholder('0')
                    ->required()
                    ->columnSpan(1),

            ]);
    }
}
