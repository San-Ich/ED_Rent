<?php

namespace App\Filament\Resources\Motors\Pages;

use App\Filament\Resources\Motors\MotorResource;
use App\Models\Motor;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListMotors extends ListRecords
{
    protected static string $resource = MotorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        return [
            'all' => Tab::make('Semua Motor'),
            'tersedia' => Tab::make('Tersedia')
                ->modifyQueryUsing(fn(Builder $query) => $query->where('status', 'tersedia'))
                ->badge(Motor::where('status', 'tersedia')->count())
                ->badgeColor('success'),
            'dipesan' => Tab::make('Sedang Dipesan')
                ->modifyQueryUsing(fn(Builder $query) => $query->where('status', 'dipesan'))
                ->badge(Motor::where('status', 'dipesan')->count())
                ->badgeColor('warning'),
            'perawatan' => Tab::make('Perawatan')
                ->modifyQueryUsing(fn(Builder $query) => $query->where('status', 'perawatan'))
                ->badge(Motor::where('status', 'perawatan')->count())
                ->badgeColor('danger'),
        ];
    }
}
