<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // 🛠️ Create the Admin
        User::create([
            'full_name' => 'System Admin',
            'email' => 'admin@ict.edu',
            'password' => Hash::make('password123'),
            'role' => 'admin',
        ]);

        // 🛠️ Create a regular Student
        // User::create([
        //     'full_name' => 'John Doe',
        //     'email' => 'student@ict.edu',
        //     'password' => Hash::make('password123'),
        //     'role' => 'student',
        // ]);
    }
}
