<?php

namespace Database\Seeders;

use App\Models\MasGradeStep;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class MasGradeStepSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $file = File::get("database/data/grade_steps.json");
        $records = json_decode($file,true);
        foreach($records as $key=>$record):
            $record['created_by'] = '99da9173-968a-4d89-877b-cb27d9adc926';
            MasGradeStep::create($record);
        endforeach;
    }
}
