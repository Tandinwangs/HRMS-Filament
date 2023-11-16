<?php

namespace App\Filament\Resources\ApprovalResource\Pages;

use App\Filament\Resources\ApprovalResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditApproval extends EditRecord
{
    protected static string $resource = ApprovalResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
