<?php

namespace App\Filament\Resources\MasDesignationResource\Pages;

use App\Filament\Resources\MasDesignationResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\DB;

class CreateMasDesignation extends CreateRecord
{
    protected static string $resource = MasDesignationResource::class;
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['created_by'] = DB::table("users")->whereRaw("email = ?",[auth()->user()->email])->value('id');
        return $data;
    }
}
