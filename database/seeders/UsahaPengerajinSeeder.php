<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsahaPengerajinSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        DB::table('usaha_pengerajin')->insert([
            ['usaha_id' => 1, 'pengerajin_id' => 1],
            ['usaha_id' => 1, 'pengerajin_id' => 2],
        ]);
    }

}
