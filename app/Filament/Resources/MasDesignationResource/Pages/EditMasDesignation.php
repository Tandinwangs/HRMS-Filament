<?php

namespace App\Filament\Resources\MasDesignationResource\Pages;

use App\Filament\Resources\MasDesignationResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\DB;

class EditMasDesignation extends EditRecord
{
    protected static string $resource = MasDesignationResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $data['edited_by'] = DB::table("users")->whereRaw("email = ?",[auth()->user()->email])->value('id');
        return $data;
    }
}
