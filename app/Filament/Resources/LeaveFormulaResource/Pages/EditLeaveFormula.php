<?php

namespace App\Filament\Resources\LeaveFormulaResource\Pages;

use App\Filament\Resources\LeaveFormulaResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditLeaveFormula extends EditRecord
{
    protected static string $resource = LeaveFormulaResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
