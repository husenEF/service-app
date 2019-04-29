<?php

use Illuminate\Database\Seeder;
use App\User;
use App\Tire;

class ServiceTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::all()->count();
        $tire = Tire::all()->count();
    }
    
}
