<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Transaction;
use App\Models\User;
use Faker\Factory as Faker;

class TransactionSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();

        // Ambil semua user_id dengan role customer
        $customerIds = User::where('role', 'customer')->pluck('id')->toArray();

        // Kalau tidak ada customer, fallback ke semua user
        if (empty($customerIds)) {
            $customerIds = User::pluck('id')->toArray();
        }

        for ($i = 0; $i < 300; $i++) {
            Transaction::create([
                'user_id' => $faker->randomElement($customerIds),
                'tanggal_transaksi' => $faker->dateTimeBetween('-12 months', 'now'),
                'status' => $faker->randomElement(['pending', 'paid', 'cancelled']),
                'total' => 0, // total nanti diupdate setelah detail dibuat
            ]);
        }
    }
}
