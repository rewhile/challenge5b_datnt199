<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create teacher accounts
        User::create([
            'username' => 'teacher1',
            'fullname' => 'Teacher One',
            'email' => 'teacher1@example.com',
            'phone' => '1234567890',
            'role' => 'teacher',
            'password' => Hash::make('123456a@A'),
        ]);

        User::create([
            'username' => 'teacher2',
            'fullname' => 'Teacher Two',
            'email' => 'teacher2@example.com',
            'phone' => '0987654321',
            'role' => 'teacher',
            'password' => Hash::make('123456a@A'),
        ]);

        // Create student accounts
        User::create([
            'username' => 'student1',
            'fullname' => 'Student One',
            'email' => 'student1@example.com',
            'phone' => '1122334455',
            'role' => 'student',
            'password' => Hash::make('123456a@A'),
        ]);

        User::create([
            'username' => 'student2',
            'fullname' => 'Student Two',
            'email' => 'student2@example.com',
            'phone' => '5566778899',
            'role' => 'student',
            'password' => Hash::make('123456a@A'),
        ]);
    }
}
