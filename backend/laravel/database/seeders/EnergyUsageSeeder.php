<?php
namespace Database\Seeders;

use App\Models\Device;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\DB;

class EnergyUsageSeeder extends Seeder{

    public function run() : void 
    {
        DB::table('energy_usage')->truncate();

        $faker = Faker::create();

        $devices = Device::all();

        if($devices->count() > 0){
            foreach ($devices as $device) {
                // Création de 5 enregistrements de consommation pour chaque appareil
                for ($i = 0; $i < 5; $i++) {
                    DB::table('energy_usage')->insert([
                        'device_id' => $device->id,
                        'consumption' => $faker->randomFloat(2, 0.1, 5), // Consommation entre 0.1 et 5 kWh
                        'recorded_at' => $faker->dateTimeThisYear, // Date et heure aléatoires cette année
                    ]);
                }
            }
        }
    }
};