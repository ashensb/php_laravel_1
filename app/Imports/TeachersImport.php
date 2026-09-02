<?php

namespace App\Imports;

use App\Models\Teacher;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class TeachersImport implements ToModel, WithHeadingRow, WithValidation
{
    public function model(array $row)
    {
        // 1. Create or fetch User Account for Teacher Login
        User::firstOrCreate(
            ['email' => $row['email_address']],
            [
                'name'     => $row['full_name'],
                'password' => Hash::make($row['password'] ?? '12345678'),
                'role'     => 'teacher',
            ]
        );

        // 2. Create Teacher Record
        return new Teacher([
            'name'          => $row['full_name'],
            'email'         => $row['email_address'],
            'phone'         => $row['phone_number'] ?? null,
            'qualification' => $row['qualification'] ?? null,
            'password'      => $row['password'] ?? '12345678',
            'img'           => 'Teacher/Profile/default.png',
        ]);
    }

    public function rules(): array
    {
        return [
            'full_name'     => 'required|string|max:255',
            'email_address' => 'required|email|unique:teachers,email',
            'phone_number'  => 'nullable',
            'qualification' => 'nullable|string',
        ];
    }
}