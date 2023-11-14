<?php

namespace App\Filament\Resources\ExpenseApplicationResource\Pages;

use App\Filament\Resources\ExpenseApplicationResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateExpenseApplication extends CreateRecord
{
    protected static string $resource = ExpenseApplicationResource::class;
}
