<?php

namespace App\Filament\Resources\TransferClaimResource\Pages;

use App\Filament\Resources\TransferClaimResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTransferClaims extends ListRecords
{
    protected static string $resource = TransferClaimResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
