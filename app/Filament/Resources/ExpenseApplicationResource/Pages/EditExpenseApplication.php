<?php

namespace App\Filament\Resources\ExpenseApplicationResource\Pages;

use App\Filament\Resources\ExpenseApplicationResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditExpenseApplication extends EditRecord
{
    protected static string $resource = ExpenseApplicationResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
