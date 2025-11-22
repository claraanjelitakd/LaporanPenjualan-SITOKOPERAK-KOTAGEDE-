<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FotoProdukSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        DB::table('foto_produk')->insert([
            ['kode_foto_produk' => 'FP001', 'produk_id' => 1, 'file_foto_produk' => 'produk1.jpg'],
            ['kode_foto_produk' => 'FP002', 'produk_id' => 2, 'file_foto_produk' => 'produk2.jpg'],
        ]);
    }

}
