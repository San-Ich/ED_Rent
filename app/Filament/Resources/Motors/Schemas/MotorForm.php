<?php

namespace App\Filament\Resources\Motors\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class MotorForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('category_id')
                        ->relationship('category', 'name')
                        ->searchable()
                        ->preload()
                        ->required(),
                    
                    TextInput::make('brand')
                        ->required()
                        ->maxLength(255),
                    
                    TextInput::make('model')
                        ->required()
                        ->maxLength(255),
                    
                    TextInput::make('plate_nomor')
                        ->label('Plat Nomor')
                        ->required()
                        ->unique(ignoreRecord: true)
                        ->maxLength(255),
                    
                    TextInput::make('harga_per_hari')
                        ->numeric()
                        ->prefix('Rp')
                        ->required(),
                    
                    Select::make('status')
                        ->options([
                            'tersedia' => 'Tersedia',
                            'perawatan' => 'perawatan',
                            'dipesan' => 'Dipesan',
                        ])
                        ->default('tersedia')
                        ->required(),

                    FileUpload::make('image')
                        ->image()
                        ->directory('motors')
                        ->columnSpanFull(),
            ]);
    }
}
