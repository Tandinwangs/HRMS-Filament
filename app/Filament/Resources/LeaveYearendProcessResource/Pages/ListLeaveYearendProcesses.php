<?php

namespace App\Filament\Resources\LeaveYearendProcessResource\Pages;

use App\Filament\Resources\LeaveYearendProcessResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListLeaveYearendProcesses extends ListRecords
{
    protected static string $resource = LeaveYearendProcessResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
