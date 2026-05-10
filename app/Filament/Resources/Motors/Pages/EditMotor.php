<?php

namespace App\Filament\Resources\Motors\Pages;

use App\Filament\Resources\Motors\MotorResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Resources\Pages\EditRecord;

class EditMotor extends EditRecord
{
    protected static string $resource = MotorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }
}
