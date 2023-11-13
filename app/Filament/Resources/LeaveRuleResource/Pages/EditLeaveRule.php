<?php

namespace App\Filament\Resources\LeaveRuleResource\Pages;

use App\Filament\Resources\LeaveRuleResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditLeaveRule extends EditRecord
{
    protected static string $resource = LeaveRuleResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
