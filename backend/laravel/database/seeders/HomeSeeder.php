<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Home;

class HomeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Insérer des données dans la table homes
        Home::create([
            'name' => 'Maison principale', // Remplacez par le nom de la maison
            'address' => '123 Rue de la Maison', // Remplacez par une adresse 
        ]);

        Home::create([
            'name' => 'Maison secondaire',
            'address' => '456 Avenue des Champs',
            'city' => 'Lyon',
            'postal_code' => '69001',
            'country' => 'France',
        ]);

        // Ajoutez d'autres maisons ici si nécessaire
    }
}
