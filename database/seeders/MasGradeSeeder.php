<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MasGradeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::insert("INSERT INTO `mas_grades` (`id`, `name`, `status`,`created_by`) VALUES ('964aac43-3756-11ee-a4b3-40a6b7ad22dc', 'E0', 1,'99da9173-968a-4d89-877b-cb27d9adc926');");
        DB::insert("INSERT INTO `mas_grades` (`id`, `name`, `status`,`created_by`) VALUES ('a45fd040-3756-11ee-a4b3-40a6b7ad22dc', 'T1', 1,'99da9173-968a-4d89-877b-cb27d9adc926');");
        DB::insert("INSERT INTO `mas_grades` (`id`, `name`, `status`,`created_by`) VALUES ('e94d5df0-3756-11ee-a4b3-40a6b7ad22dc', 'T2', 1,'99da9173-968a-4d89-877b-cb27d9adc926');");
        DB::insert("INSERT INTO `mas_grades` (`id`, `name`, `status`,`created_by`) VALUES ('f341ae96-3756-11ee-a4b3-40a6b7ad22dc', 'S', 1,'99da9173-968a-4d89-877b-cb27d9adc926');");
        DB::insert("INSERT INTO `mas_grades` (`id`, `name`, `status`,`created_by`) VALUES ('fa4e1311-3756-11ee-a4b3-40a6b7ad22dc', 'P', 1,'99da9173-968a-4d89-877b-cb27d9adc926');");
    }
}
