<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Device;
use App\Models\UsersAction;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class LogsSeeder extends Seeder
{
    public function run() : void
    {
        DB::table('logs')->truncate();

        $faker = Faker::create();

        $devices = Device::all();
        $users = User::all();
        $actions = User::all();

        if($users->count() > 0 && $devices->count() && $actions->count()){
            for($i = 0 ; $i < 20 ; $i++){
                $user = $users->random();
                $device = $devices->random();
                $action = $actions->random();

                DB::table('logs')->insert([
                    'user_id'=> $user->id,
                    'device_id' => $device->id,
                    'user_action_id' => $action->id,
                    'log_message' => $faker->sentence,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

    }
}