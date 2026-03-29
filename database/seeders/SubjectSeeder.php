<?php

namespace Database\Seeders;

use App\Models\Subject;
use Illuminate\Database\Seeder;

class SubjectSeeder extends Seeder
{
    public function run(): void
    {
        $subjects = [
            ['code' => 'CSE110', 'name' => 'Programming Language I'],
            ['code' => 'CSE111', 'name' => 'Programming Language II'],
            ['code' => 'CSE220', 'name' => 'Data Structures'],
            ['code' => 'CSE221', 'name' => 'Algorithms'],
            ['code' => 'CSE250', 'name' => 'Circuits and Electronics'],
            ['code' => 'CSE251', 'name' => 'Electronic Devices and Circuits'],
            ['code' => 'CSE330', 'name' => 'Numerical Methods'],
            ['code' => 'CSE370', 'name' => 'Database Systems'],
            ['code' => 'CSE420', 'name' => 'Compiler Design'],
            ['code' => 'CSE470', 'name' => 'Software Engineering'],
            ['code' => 'MAT120', 'name' => 'Calculus and Analytical Geometry'],
            ['code' => 'MAT215', 'name' => 'Linear Algebra'],
            ['code' => 'PHY111', 'name' => 'Principles of Physics I'],
            ['code' => 'ENG101', 'name' => 'English Composition'],
        ];

        foreach ($subjects as $subject) {
            Subject::updateOrCreate(
                ['code' => $subject['code']],
                ['name' => $subject['name']]
            );
        }
    }
}
