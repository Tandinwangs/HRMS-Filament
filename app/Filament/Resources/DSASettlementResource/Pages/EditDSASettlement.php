<?php

namespace App\Filament\Resources\DSASettlementResource\Pages;

use App\Filament\Resources\DSASettlementResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDSASettlement extends EditRecord
{
    protected static string $resource = DSASettlementResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
