<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class JenisUsahaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        DB::table('jenis_usaha')->insert([
            ['kode_jenis_usaha' => 'JU001', 'nama_jenis_usaha' => 'Kerajinan Perak'],
            ['kode_jenis_usaha' => 'JU002', 'nama_jenis_usaha' => 'Perhiasan Modern'],
        ]);
    }

}
