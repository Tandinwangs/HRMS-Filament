<?php

namespace App\Filament\Resources\LeaveApprovalRuleResource\Pages;

use App\Filament\Resources\LeaveApprovalRuleResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListLeaveApprovalRules extends ListRecords
{
    protected static string $resource = LeaveApprovalRuleResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
