<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\Batch;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Admin Account
        User::create([
            'name' => 'System Admin',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('123456'),
            'role' => 'admin',
        ]);

        // 2. Sample Batches
        $batch1 = Batch::create([
            'name' => 'Batch 01',
            'course_name' => 'Full Stack Web Development',
            'start_date' => now(),
        ]);

        $batch2 = Batch::create([
            'name' => 'Batch 02',
            'course_name' => 'Data Science & AI',
            'start_date' => now(),
        ]);

        // 3. Sample Teacher
        Teacher::create([
            'name' => 'Nimal Munasingha',
            'email' => 'nimal@gmail.com',
            'qualification' => 'BSc in Computer Science',
        ]);

        // 4. Sample Students 
        Student::create([
            'reg_no' => 'STU-1001',
            'name' => 'Dewmi Silva',
            'email' => 'dewmi@gmail.com',
            'batch_id' => $batch1->id,
            'created_at' => now(),
        ]);

        Student::create([
            'reg_no' => 'STU-1002',
            'name' => 'Kusal Mendis',
            'email' => 'kusal@gmail.com', 
            'batch_id' => $batch2->id,
            'created_at' => now(),
        ]);
    }
}