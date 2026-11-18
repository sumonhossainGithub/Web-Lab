<?php

namespace Database\Seeders;

use App\Models\Result;
use App\Models\Student;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Create Default Portal Administrator User for Auth Testing
        $admin = User::firstOrCreate(
            ['email' => 'admin@portal.com'],
            [
                'name' => 'System Administrator',
                'password' => Hash::make('password'),
            ]
        );

        // 2. Create Sample Students
        $studentsData = [
            [
                'name' => 'Sarah Jenkins',
                'roll' => 'CSE-2026-001',
                'department' => 'Computer Science & Engineering',
                'email' => 'sarah.jenkins@portal.edu',
                'results' => [
                    ['course_code' => 'CSE-101', 'marks' => 88.5],
                    ['course_code' => 'CSE-102', 'marks' => 78.0],
                    ['course_code' => 'MAT-201', 'marks' => 92.0],
                    ['course_code' => 'ENG-101', 'marks' => 74.0],
                ]
            ],
            [
                'name' => 'Michael Chen',
                'roll' => 'EEE-2026-042',
                'department' => 'Electrical & Electronics Engineering',
                'email' => 'michael.chen@portal.edu',
                'results' => [
                    ['course_code' => 'EEE-201', 'marks' => 82.0],
                    ['course_code' => 'EEE-202', 'marks' => 67.5],
                    ['course_code' => 'PHY-101', 'marks' => 63.0],
                ]
            ],
            [
                'name' => 'Amara Vance',
                'roll' => 'BBA-2026-105',
                'department' => 'Business Administration',
                'email' => 'amara.vance@portal.edu',
                'results' => [
                    ['course_code' => 'BBA-101', 'marks' => 76.0],
                    ['course_code' => 'BBA-102', 'marks' => 85.0],
                    ['course_code' => 'ECO-101', 'marks' => 58.0],
                ]
            ],
        ];

        foreach ($studentsData as $data) {
            $results = $data['results'];
            unset($data['results']);

            $student = Student::firstOrCreate(
                ['roll' => $data['roll']],
                $data
            );

            // Populate initial course results (automatic grade calculation will trigger)
            foreach ($results as $res) {
                Result::firstOrCreate(
                    [
                        'student_id' => $student->id,
                        'course_code' => $res['course_code']
                    ],
                    [
                        'marks' => $res['marks'],
                        'grade' => Result::calculateGrade($res['marks']),
                    ]
                );
            }
        }
    }
}
