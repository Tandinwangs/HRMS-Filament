<?php

namespace App\Filament\Resources\AdvanceTypeResource\Pages;

use App\Filament\Resources\AdvanceTypeResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAdvanceType extends EditRecord
{
    protected static string $resource = AdvanceTypeResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
