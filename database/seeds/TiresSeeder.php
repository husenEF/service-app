<?php

use Illuminate\Database\Seeder;
use App\Vehicle;
use App\User;
use Faker\Factory as Faker;

class TiresSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $vehicle = Vehicle::all()->count();
        $user = User::all()->count();
        $faker = Faker::create();
        foreach (range(0, 25) as $i) {
            DB::table('tires')->insert([
                // 'id_vehicle' => $faker->biasedNumberBetween(1, $vehicle),
                'created_by' => $faker->biasedNumberBetween(1, $user),
                // "posistion" => rand(1, 15),
                "merek" => $faker->company(),
                "ukuran" => rand(15, 17),
                "nomor" => $faker->randomDigitNotNull,
                "stempel" => $faker->word,
                "buy_date" => $faker->dateTime("now"),
                "created_at" => $faker->dateTime("now"),
                "images" => $faker->image("public/media/tires", 400, 300, 'transport', false)
            ]);
        }
    }
}
