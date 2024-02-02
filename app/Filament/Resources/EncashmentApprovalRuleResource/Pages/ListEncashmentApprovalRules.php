<?php

namespace App\Filament\Resources\EncashmentApprovalRuleResource\Pages;

use App\Filament\Resources\EncashmentApprovalRuleResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListEncashmentApprovalRules extends ListRecords
{
    protected static string $resource = EncashmentApprovalRuleResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
