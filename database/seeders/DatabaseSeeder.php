<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            JenisUsahaSeeder::class,
            UsahaSeeder::class,
            KategoriProdukSeeder::class,
            PengerajinSeeder::class,
            ProdukSeeder::class,
            UsahaJenisSeeder::class,
            UsahaPengerajinSeeder::class,
            UsahaProdukSeeder::class,
            FotoProdukSeeder::class,
            TransactionSeeder::class,       // transaksi dibuat dulu
            DetailTransaksiSeeder::class,   // baru detail transaksi
            // tambahkan seeder lain kalau ada
        ]);
    }
}
