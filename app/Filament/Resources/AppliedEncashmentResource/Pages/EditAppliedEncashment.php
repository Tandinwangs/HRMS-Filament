<?php

namespace App\Filament\Resources\AppliedEncashmentResource\Pages;

use App\Filament\Resources\AppliedEncashmentResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAppliedEncashment extends EditRecord
{
    protected static string $resource = AppliedEncashmentResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
