<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PengerajinSeeder extends Seeder
{
    public function run(): void
    {
        for ($i = 0; $i < 10; $i++) {
            DB::table('pengerajin')->insert([
                'kode_pengerajin'   => 'PG-' . strtoupper(Str::random(5)),
                'nama_pengerajin'   => Str::random(10),
                'jk_pengerajin'     => rand(0, 1) ? 'P' : 'W',
                'usia_pengerajin'   => rand(20, 60),
                'telp_pengerajin'   => '08' . rand(100000000, 999999999),
                'email_pengerajin'  => Str::random(8).'@gmail.com',
                'alamat_pengerajin' => 'Jl. ' . Str::random(12),
                'created_at'        => now(),
                'updated_at'        => now(),
            ]);
        }
    }
}
