<?php

use Illuminate\Database\Seeder;

use App\Admin;
use App\User;

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
            'password' => bcrypt('password'),
        ]);

        User::truncate();
        User::create([
            'name' => 'Charles Salinas',
            'email' => 'salinasandrei45@gmail.com',
            'password' => bcrypt('password'),
            'active' => true,
            'activation_token' => str_random(60)
        ]);
    }
}
