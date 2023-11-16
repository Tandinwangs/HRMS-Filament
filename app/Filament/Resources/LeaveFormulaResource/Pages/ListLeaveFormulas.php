<?php

namespace App\Filament\Resources\LeaveFormulaResource\Pages;

use App\Filament\Resources\LeaveFormulaResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListLeaveFormulas extends ListRecords
{
    protected static string $resource = LeaveFormulaResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
