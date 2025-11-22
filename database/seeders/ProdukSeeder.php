<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class ProdukSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $kategoriIds = DB::table('kategori_produk')->pluck('id')->toArray();
        $produk = [];

        $faker = Faker::create();

        for ($i = 1; $i <= 100; $i++) {
            $produk[] = [
            'kode_produk' => 'PRD' . str_pad($i, 3, '0', STR_PAD_LEFT),
            'kategori_produk_id' => !empty($kategoriIds) ? $faker->randomElement($kategoriIds) : null,
            'nama_produk' => 'Produk ' . strtoupper(\Illuminate\Support\Str::random(5)),
            'deskripsi' => $faker->sentence(),
            'harga' => $faker->numberBetween(100000, 15000000),
            'stok' => $faker->numberBetween(1, 50),
            'slug' => \Illuminate\Support\Str::slug('produk-' . $i . '-' . $faker->word()),
            'created_at' => now(),
            'updated_at' => now(),
            ];
        }

        if (!empty($produk)) {
            DB::table('produk')->insert($produk);
        }
    }
}
