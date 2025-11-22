<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class KategoriProdukSeeder extends Seeder
{
    public function run(): void
    {
        $kategori = [
            ['kode_kategori_produk' => 'KTG001', 'nama_kategori_produk' => 'Tatah', 'slug' => 'tatah'],
            ['kode_kategori_produk' => 'KTG002', 'nama_kategori_produk' => 'Filigeri', 'slug' => 'filigeri'],
            ['kode_kategori_produk' => 'KTG003', 'nama_kategori_produk' => 'Wedding Ring', 'slug' => 'wedding-ring'],
            ['kode_kategori_produk' => 'KTG004', 'nama_kategori_produk' => 'Cincin Akik', 'slug' => 'cincin-akik'],
            ['kode_kategori_produk' => 'KTG005', 'nama_kategori_produk' => 'Aksesoris Manten', 'slug' => 'aksesoris-manten'],
            ['kode_kategori_produk' => 'KTG006', 'nama_kategori_produk' => 'Souvenir', 'slug' => 'souvenir'],

            // --- Tambahan realistis ---
            ['kode_kategori_produk' => 'KTG007', 'nama_kategori_produk' => 'Kalung', 'slug' => 'kalung'],
            ['kode_kategori_produk' => 'KTG008', 'nama_kategori_produk' => 'Gelang', 'slug' => 'gelang'],
            ['kode_kategori_produk' => 'KTG009', 'nama_kategori_produk' => 'Liontin', 'slug' => 'liontin'],
            ['kode_kategori_produk' => 'KTG010', 'nama_kategori_produk' => 'Anting', 'slug' => 'anting'],
            ['kode_kategori_produk' => 'KTG011', 'nama_kategori_produk' => 'Brooch', 'slug' => 'brooch'],
            ['kode_kategori_produk' => 'KTG012', 'nama_kategori_produk' => 'Aksesoris Fashion', 'slug' => 'aksesoris-fashion'],
            ['kode_kategori_produk' => 'KTG013', 'nama_kategori_produk' => 'Perhiasan Custom', 'slug' => 'perhiasan-custom'],
            ['kode_kategori_produk' => 'KTG014', 'nama_kategori_produk' => 'Luxury Edition', 'slug' => 'luxury-edition'],
            ['kode_kategori_produk' => 'KTG015', 'nama_kategori_produk' => 'Miniatur Seni', 'slug' => 'miniatur-seni'],
        ];

        foreach ($kategori as $k) {
            DB::table('kategori_produk')->insert([
                'kode_kategori_produk' => $k['kode_kategori_produk'],
                'nama_kategori_produk' => $k['nama_kategori_produk'],
                'slug' => $k['slug'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
