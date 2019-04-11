<?php

use Illuminate\Database\Seeder;

use App\Admin;
use App\Citizen;
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
    }
}
