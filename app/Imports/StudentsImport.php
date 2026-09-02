<?php

namespace App\Imports;

use App\Models\Student;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class StudentsImport implements ToModel, WithHeadingRow, WithValidation
{
    public function model(array $row)
    {
        $dob = is_numeric($row['dob']) 
            ? Date::excelToDateTimeObject($row['dob'])->format('Y-m-d') 
            : date('Y-m-d', strtotime($row['dob']));

       
        User::firstOrCreate(
            ['email' => $row['email_address']],
            [
                'name'     => $row['full_name'],
                'password' => Hash::make($row['password'] ?? '12345678'),
                'role'     => 'student',
            ]
        );

       
        return new Student([
            'reg_no'   => $row['registration_no'],
            'name'     => $row['full_name'],
            'email'    => $row['email_address'],
            'batch_id' => $row['batch_id'],
            'dob'      => $dob,
            'age'      => $row['age'],
            'password' => $row['password'] ?? '12345678',
            'img'      => 'Student/Profile/default.png',
        ]);
    }

    public function rules(): array
    {
        return [
            'registration_no' => 'required|unique:students,reg_no',
            'full_name'       => 'required|string',
            'email_address'   => 'required|email|unique:students,email', 
            'batch_id'        => 'required|exists:batches,id',
            'dob'             => 'required',
            'age'             => 'required|integer',
        ];
    }
}