<?php

namespace App\Filament\Resources\EncashmentApprovalResource\Pages;

use App\Filament\Resources\EncashmentApprovalResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListEncashmentApprovals extends ListRecords
{
    protected static string $resource = EncashmentApprovalResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
