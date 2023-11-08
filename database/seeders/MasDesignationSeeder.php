<?php

namespace Database\Seeders;

use App\Models\MasDesignation;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class MasDesignationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $file = File::get("database/data/designations.json");
        $designations = json_decode($file,true);
        $superAdminId =DB::table("users")->whereRaw("email = 'sw_engineer1.sdu@tashicell.com'")->value('id');
        foreach($designations as $designation):
            $designation['created_by'] = $superAdminId;
            MasDesignation::create($designation);
        endforeach;
    }
}
