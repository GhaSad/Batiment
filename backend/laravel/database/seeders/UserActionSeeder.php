<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Device;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\DB;

class UserActionSeeder extends Seeder{
    public function run() : void
    {
        $faker = Faker::create();

        $devices = Device::all();
        $users = User::all();

        if($devices->count()>0 and $user->count()>0){
            for($i = 0 ; $i < 20; $i++){
                $user = $users->random();
                $device= $devices->random();

                DB::table('user_actions')->insert([
                    'device_id' => $device->id,
                    'user_id' => $user->id,
                    'action_type' => $faker->randomElement('allumer','éteindre','ajuster','autre'),
                    'value' => $faker->optional()->word,
                    'action_time' => $faker->dateTimeThisYear, 
                ]);
            }
        }
    }
}