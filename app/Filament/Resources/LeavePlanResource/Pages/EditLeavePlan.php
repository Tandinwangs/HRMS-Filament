<?php

namespace App\Filament\Resources\LeavePlanResource\Pages;

use App\Filament\Resources\LeavePlanResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditLeavePlan extends EditRecord
{
    protected static string $resource = LeavePlanResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
