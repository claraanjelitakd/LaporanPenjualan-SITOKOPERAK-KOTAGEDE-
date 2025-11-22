<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class UsahaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i = 1; $i <= 10; $i++) {
            DB::table('usaha')->insert([
                'kode_usaha'      => 'US' . str_pad($i, 3, '0', STR_PAD_LEFT), // contoh: US001
                'nama_usaha'      => 'Usaha ' . Str::upper(Str::random(5)),
                'telp_usaha'      => '08' . rand(100000000,999999999),
                'email_usaha'     => 'usaha'.$i.'@gmail.com',
                'deskripsi_usaha' => 'Deskripsi singkat untuk usaha ke-' . $i,
                'foto_usaha'      => null,
                'link_gmap_usaha' => 'https://maps.google.com/?q=Usaha+' . $i,
                'status_usaha'    => 'aktif',
                'created_at'      => now(),
                'updated_at'      => now(),
            ]);
        }
    }
}
