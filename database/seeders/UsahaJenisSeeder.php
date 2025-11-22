<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsahaJenisSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        DB::table('usaha_jenis')->insert([
            ['usaha_id' => 1, 'jenis_usaha_id' => 1],
            ['usaha_id' => 1, 'jenis_usaha_id' => 2],
        ]);
    }

}
