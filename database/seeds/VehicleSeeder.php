<?php

use Illuminate\Database\Seeder;

use App\User;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;

class VehicleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::all()->count();
        $faker = Faker::create();
        // $cars = ["bus", "truck", "car", "trailer"];
        foreach (range(0, 10) as $i) {
            $fakeId = $faker->randomDigit();
            // $type = array_rand($cars);
            $userId =  $faker->biasedNumberBetween(1, $user);
            DB::table('vehicles')->insert([
                'user_id' => $userId,
                'merek' => $faker->name,
                'size' => $faker->numberBetween(9, 30),
                'platnumber' => "AA" . $faker->numberBetween(100, 9999) . "CF",
                "created_by" => $userId,
                // "type" => $cars[$type]
                // 'email' => $faker->email,
                // 'password' => Hash::make("123123"),
                // 'roles' => 'mekanik',
            ]);
        }
    }
}
