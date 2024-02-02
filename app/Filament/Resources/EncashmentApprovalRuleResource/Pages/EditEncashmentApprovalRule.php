<?php

namespace App\Filament\Resources\EncashmentApprovalRuleResource\Pages;

use App\Filament\Resources\EncashmentApprovalRuleResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditEncashmentApprovalRule extends EditRecord
{
    protected static string $resource = EncashmentApprovalRuleResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
