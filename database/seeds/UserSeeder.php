<?php
// database/seeders/UserSeeder.php

//namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Create admin user
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@dms.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // Create manager user
        User::create([
            'name' => 'Manager User',
            'email' => 'manager@dms.com',
            'password' => Hash::make('password'),
            'role' => 'manager',
        ]);

        // Create regular user
        User::create([
            'name' => 'Regular User',
            'email' => 'user@dms.com',
            'password' => Hash::make('password'),
            'role' => 'user',
        ]);
    }
}