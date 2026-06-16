<?php

namespace App\Filament\Resources\Perlengkapans;

use App\Filament\Resources\Perlengkapans\Pages\CreatePerlengkapan;
use App\Filament\Resources\Perlengkapans\Pages\EditPerlengkapan;
use App\Filament\Resources\Perlengkapans\Pages\ListPerlengkapans;
use App\Filament\Resources\Perlengkapans\Schemas\PerlengkapanForm;
use App\Filament\Resources\Perlengkapans\Tables\PerlengkapansTable;
use App\Models\Perlengkapan;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class PerlengkapanResource extends Resource
{
    protected static ?string $model = Perlengkapan::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'perlengkapan_id';

    public static function form(Schema $schema): Schema
    {
        return PerlengkapanForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PerlengkapansTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPerlengkapans::route('/'),
            'create' => CreatePerlengkapan::route('/create'),
            'edit' => EditPerlengkapan::route('/{record}/edit'),
        ];
    }
}
