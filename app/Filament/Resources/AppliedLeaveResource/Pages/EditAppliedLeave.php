<?php

namespace App\Filament\Resources\AppliedLeaveResource\Pages;

use App\Filament\Resources\AppliedLeaveResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAppliedLeave extends EditRecord
{
    protected static string $resource = AppliedLeaveResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
