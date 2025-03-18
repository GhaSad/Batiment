<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    public function run()
    {

        $faker = Faker::create();
        
        for ($i = 0; $i < 10; $i++) {
            $user = User::create([
                'username' => $faker->userName,
                'email' => $faker->unique()->safeEmail,
                'password' => Hash::make('password123'), // Mot de passe par défaut
                'role' => $faker->randomElement(['visiteur','simple','complexe','admin']),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Insertion des tokens de réinitialisation de mot de passe
            DB::table('password_reset_tokens')->insert([
                'email' => $user->email,
                'token' => $faker->bin2hex(random_bytes(32)),
                'created_at' => now(),
            ]);

            //Insetion des sessions pour chaque utilisateur
            DB::table('sessions')->insert([
                'id' => bin2hex(random_bytes(16)), //ID de session aléatoire
                'user_id' => $user->id,
                'ip_adress' => $faker->ipv4,
                'user_agent' => $faker->userAgent,
                'payload' => json_encode(['some'=> 'data'] ),
                'last_activity' => now()->timestamp,
            ]);
        }
    }
}
