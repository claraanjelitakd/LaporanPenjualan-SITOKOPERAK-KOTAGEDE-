<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class UsahaProdukSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $faker = Faker::create();
        $usahaIds = DB::table('usaha')->pluck('id')->toArray();
        $produkIds = DB::table('produk')->pluck('id')->toArray();

        if (empty($usahaIds) || empty($produkIds)) {
            return;
        }

        $rows = [];
        foreach ($produkIds as $produkId) {
            $rows[] = [
                'usaha_id' => $faker->randomElement($usahaIds),
                'produk_id' => $produkId
            ];
        }

        DB::table('usaha_produk')->insert($rows);
    }

}
