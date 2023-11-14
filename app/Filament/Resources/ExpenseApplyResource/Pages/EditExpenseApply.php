<?php

namespace App\Filament\Resources\ExpenseApplyResource\Pages;

use App\Filament\Resources\ExpenseApplyResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditExpenseApply extends EditRecord
{
    protected static string $resource = ExpenseApplyResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
