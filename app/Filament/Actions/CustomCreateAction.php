<?php
// app/Filament/Actions/CustomCreateAction.php

namespace App\Filament\Actions;

use Filament\Tables\Actions\CreateAction as BaseCreateAction;

class CustomCreateAction extends BaseCreateAction
{
    public function authorize($abilities, $arguments = null): static
    {
        // Check if related data exists for the leavePolicy
        // You'll need to adjust this logic based on your relationships
        return !$arguments || $arguments->relatedData()->doesntExist();
    }
}
