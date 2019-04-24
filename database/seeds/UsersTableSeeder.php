<?php

use Illuminate\Database\Seeder;

use App\User;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin = User::where(['roles' => 'admin'])->first();
        if (!$admin) {
            DB::table('users')->insert([
                'name' => "admin",
                'email' => "admin@mail.com",
                'username'=>'admin',
                'password' => Hash::make("admin123"),
                'roles' => 'admin',
                'active' => 1
            ]);
        }
        $faker = Faker::create();
        foreach (range(0, 10) as $i) {
            DB::table('users')->insert([
                'name' => $faker->name,
                'email' => $faker->email,
                'username'=>$faker->userName,
                'password' => Hash::make("123123"),
                'roles' => 'mekanik',
                'active' => rand(0, 1)
            ]);
        }
    }
}
