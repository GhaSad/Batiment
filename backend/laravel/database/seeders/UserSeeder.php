<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run()
    {
        // User::factory(10)->create();

        $faker = Faker::create();
        
        for ($i = 0; $i < 10; $i++) {
            User::create([
                'username' => $faker->name,
                'email' => $faker->unique()->safeEmail,
                'password' => Hash::make('password'), // Mot de passe par défaut
            ]);
        }
    }
}
