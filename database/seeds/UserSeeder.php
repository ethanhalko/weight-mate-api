<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'first_name' => 'OEV',
            'last_name' => 'admin',
            'password' => Hash::make('temp'),
            'initial_weight' => 100.5,
            'active' => false,
            'admin' => true
        ]);
    }
}
