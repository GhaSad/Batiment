<?php
namespace Database\Seeders;

use App\Models\Device;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\DB;

class EnergyUsageSeeder extends Seeder
{
    public function run() : void 
    {
        // Truncate la table 'energy_usage' pour éviter les doublons lors de l'exécution du seeder
        DB::table('energy_usage')->truncate();

        // Initialisation de Faker pour générer des données aléatoires
        $faker = Faker::create();

        // Récupérer tous les dispositifs de la base
        $devices = Device::all();

        // Vérifier si des dispositifs existent dans la table
        if ($devices->isNotEmpty()) {
            foreach ($devices as $device) {
                // Récupérer les informations supplémentaires (home_id et room_id)
                $home_id = $device->home_id;  // Suppose que home_id est une relation directe

                // Création de 5 enregistrements de consommation pour chaque appareil
                for ($i = 0; $i < 5; $i++) {
                    DB::table('energy_usage')->insert([
                        'device_id' => $device->id,
                        'home_id' => $home_id,  // Ajouter le home_id
                        'consumption' => $faker->randomFloat(2, 0.1, 5), // Consommation entre 0.1 et 5 kWh
                        'recorded_at' => $faker->dateTimeThisYear, // Date et heure aléatoires cette année
                    ]);
                }
            }
        } else {
            // Message de log si aucun dispositif n'est trouvé
            \Log::warning('Aucun dispositif trouvé dans la base de données pour insérer des données de consommation.');
        }
    }
}
