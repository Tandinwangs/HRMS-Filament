<?php

namespace App\Filament\Resources\EnfrocementOptionResource\Pages;

use App\Filament\Resources\EnfrocementOptionResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditEnfrocementOption extends EditRecord
{
    protected static string $resource = EnfrocementOptionResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
