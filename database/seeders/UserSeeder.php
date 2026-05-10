<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin user
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@university.edu',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'university_id' => 'ADMIN001',
            'karma_points' => 100,
            'email_verified_at' => now(),
        ]);

        // Demo students
        $students = [
            ['name' => 'Hrithik Sharma', 'email' => 'hrithik@university.edu', 'university_id' => 'STU001', 'karma_points' => 85],
            ['name' => 'Ramisha Khan', 'email' => 'ramisha@university.edu', 'university_id' => 'STU002', 'karma_points' => 120],
            ['name' => 'Alice Johnson', 'email' => 'alice@university.edu', 'university_id' => 'STU003', 'karma_points' => 45],
            ['name' => 'Bob Williams', 'email' => 'bob@university.edu', 'university_id' => 'STU004', 'karma_points' => 60],
            ['name' => 'Carol Davis', 'email' => 'carol@university.edu', 'university_id' => 'STU005', 'karma_points' => 95],
            ['name' => 'David Lee', 'email' => 'david@university.edu', 'university_id' => 'STU006', 'karma_points' => 30],
            ['name' => 'Emma Wilson', 'email' => 'emma@university.edu', 'university_id' => 'STU007', 'karma_points' => 75],
            ['name' => 'Frank Brown', 'email' => 'frank@university.edu', 'university_id' => 'STU008', 'karma_points' => 50],
            ['name' => 'Grace Chen', 'email' => 'grace@university.edu', 'university_id' => 'STU009', 'karma_points' => 110],
            ['name' => 'Henry Taylor', 'email' => 'henry@university.edu', 'university_id' => 'STU010', 'karma_points' => 40],
        ];

        foreach ($students as $student) {
            User::create(array_merge($student, [
                'password' => Hash::make('password'),
                'role' => 'student',
                'email_verified_at' => now(),
            ]));
        }
    }
}
