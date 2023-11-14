<?php

namespace App\Filament\Resources\PolicyResource\Pages;

use App\Filament\Resources\PolicyResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPolicy extends EditRecord
{
    protected static string $resource = PolicyResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
