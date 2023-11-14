<?php

namespace App\Filament\Resources\ExpenseTypeResource\Pages;

use App\Filament\Resources\ExpenseTypeResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateExpenseType extends CreateRecord
{
    protected static string $resource = ExpenseTypeResource::class;
}
