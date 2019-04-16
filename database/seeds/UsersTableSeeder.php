<?php

use Illuminate\Database\Seeder;

use App\Admin;
use App\Citizen;
use App\FireStation;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Admin::truncate();
        Admin::create([
            'email' => 'admin@test.com',
            'password' => Hash::make('password'),
        ]);

        Citizen::truncate();
        Citizen::create([
            'name' => 'Jeric Cordova',
            'email' => 'cordovajeric@gmail.com',
            'password' => Hash::make('password'),
            'active' => true,
            'activation_token' => str_random(60)
        ]);

        FireStation::truncate();
        FireStation::create([
            'name' => 'Example Station',
            'username' => '1231234',
            'latitude' => 10.246575,
            'longitude' => 123.814931,
            'password' => Hash::make('password')
        ]);
    }
}
