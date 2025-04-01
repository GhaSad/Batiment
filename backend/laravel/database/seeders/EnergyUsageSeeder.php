<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Device;
use App\Models\EnergyUsage;
use Faker\Factory as Faker;

class EnergyUsageSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();

        // Récupère tous les dispositifs existants
        $devices = Device::all();

        foreach ($devices as $device) {
            // Vérifie si une consommation pour cet appareil existe déjà
            if (!EnergyUsage::where('device_id', $device->id)->exists()) {
                // Créer un enregistrement de consommation pour chaque dispositif
                EnergyUsage::create([
                    'device_id' => $device->id,
                    'consumption' => $faker->randomFloat(2, 0.1, 5), // Consommation aléatoire entre 0.1 et 5 kWh
                    'recorded_at' => $faker->dateTimeThisYear, // Date aléatoire de cette année
                    'home_id' => $device->home_id, // Récupère le `home_id` depuis le dispositif
                ]);
            }
        }
    }
}
