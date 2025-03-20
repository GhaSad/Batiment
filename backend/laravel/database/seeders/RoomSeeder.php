<?php

namespace Database\Seeders;

use App\Models\Room;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class RoomSeeder extends Seeder
{
    public function run() : void
    {
        DB::table('rooms')->truncate();

        $faker = Faker::create();

        for($i = 0 ; $i < 10 ; $i++){
            Room::create([
                'name' => $faker->word,
                'description' => $faker->sentence,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}