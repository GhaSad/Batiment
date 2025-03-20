<?php 

namespace Database\Seeders;

use App\Models\Device;
use App\Models\Room;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\DB;

class DeviceSeeder extends Seeder{
    public function run() : void
    {
        DB::table('devices')->truncate();

        $faker = Faker::create();

        $rooms = Room::all();

        if($rooms->count()>0){
            for($i = 0 ; $i < 10 ; $i++){
                $device = Device::create([
                    'name' => $faker->word,
                    'type' => $faker->randomElement(['thermostat','lumière','caméra','capteur','autre']),
                    'status' => $faker->randomElement(['actif','inactif']),
                    'room_id' => $rooms->random()->id, //On assigne le device a une piece aleatoire
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}