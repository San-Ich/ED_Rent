<?php

namespace App\Filament\Resources\Motors;

use App\Filament\Resources\Motors\Pages\CreateMotor;
use App\Filament\Resources\Motors\Pages\EditMotor;
use App\Filament\Resources\Motors\Pages\ListMotors;
use App\Filament\Resources\Motors\Schemas\MotorForm;
use App\Filament\Resources\Motors\Tables\MotorsTable;
use App\Models\Motor;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MotorResource extends Resource
{
    protected static ?string $navigationLabel = 'Daftar Motor'; // Ganti nama di menu
    protected static ?string $model = Motor::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'plate_nomor';

    public static function form(Schema $schema): Schema
    {
        return MotorForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return MotorsTable::configure($table);
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
            'index' => ListMotors::route('/'),
            'create' => CreateMotor::route('/create'),
            'edit' => EditMotor::route('/{record}/edit'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
