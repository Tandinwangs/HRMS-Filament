<?php

namespace App\Filament\Resources\LeavePolicyResource\Pages;

use App\Filament\Resources\LeavePolicyResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditLeavePolicy extends EditRecord
{
    protected static string $resource = LeavePolicyResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
