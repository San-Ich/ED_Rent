<?php

namespace App\Filament\Resources\Perlengkapans\Pages;

use App\Filament\Resources\Perlengkapans\PerlengkapanResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditPerlengkapan extends EditRecord
{
    protected static string $resource = PerlengkapanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
