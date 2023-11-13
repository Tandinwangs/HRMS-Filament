<?php

namespace App\Filament\Resources\LeaveYearendProcessResource\Pages;

use App\Filament\Resources\LeaveYearendProcessResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditLeaveYearendProcess extends EditRecord
{
    protected static string $resource = LeaveYearendProcessResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
