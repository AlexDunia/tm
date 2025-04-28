<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create a default admin user
        User::create([
            'firstname' => 'Admin',
            'lastname' => 'User',
            'email' => 'admin@tixdemand.com',
            'password' => Hash::make('Password@123'),
            'ipaddress' => '127.0.0.1',
            'is_admin' => 1
        ]);

        // Create a default regular user
        User::create([
            'firstname' => 'Test',
            'lastname' => 'User',
            'email' => 'user@tixdemand.com',
            'password' => Hash::make('Password@123'),
            'ipaddress' => '127.0.0.1',
            'is_admin' => 0
        ]);
    }
}
