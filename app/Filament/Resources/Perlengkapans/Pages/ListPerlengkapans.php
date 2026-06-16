<?php

namespace App\Filament\Resources\Perlengkapans\Pages;

use App\Filament\Resources\Perlengkapans\PerlengkapanResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPerlengkapans extends ListRecords
{
    protected static string $resource = PerlengkapanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
