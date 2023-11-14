<?php

namespace App\Filament\Resources\ExpenseTypeResource\Pages;

use App\Filament\Resources\ExpenseTypeResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListExpenseTypes extends ListRecords
{
    protected static string $resource = ExpenseTypeResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
