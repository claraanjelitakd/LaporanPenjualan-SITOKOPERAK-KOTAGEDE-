<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Faker\Factory as Faker;
class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // User::create([
        //     'name' => 'Admin User',
        //     'email' => 'admin@example.com',
        //     'password' => Hash::make('password'), // WAJIB di-hash
        //     'role' => 'admin',
        // ]);

        // User::create([
        //     'name' => 'Guest User',
        //     'email' => 'guest@example.com',
        //     'password' => Hash::make('password'), // WAJIB di-hash juga
        //     'role' => 'guest',
        // ]);

        User::create([
            'username' => 'admin',
            'email' => 'admin123@example.com',
            'password' => Hash::make('12345'), // WAJIB di-hash
            'role' => 'admin',
        ]);

        // Faker instance
        $faker = Faker::create();

        // 30 dummy customers
        for ($i = 1; $i <= 30; $i++) {
            User::create([
                'username' => $faker->userName,
                'email' => $faker->unique()->safeEmail,
                'password' => Hash::make('12345'),
                'role' => 'customer',
            ]);
        }
    }
}
