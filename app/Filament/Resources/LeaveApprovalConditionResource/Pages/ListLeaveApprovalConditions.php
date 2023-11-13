<?php

namespace App\Filament\Resources\LeaveApprovalConditionResource\Pages;

use App\Filament\Resources\LeaveApprovalConditionResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListLeaveApprovalConditions extends ListRecords
{
    protected static string $resource = LeaveApprovalConditionResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
