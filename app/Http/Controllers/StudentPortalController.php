<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use Illuminate\Support\Facades\Auth;

class StudentPortalController extends Controller
{
    public function index()
    {
        // Log වී සිටින User ගේ Email එක ලබාගැනීම
        $loggedUserEmail = Auth::user()->email;

        // Email එකට අදාළ Student Record එක Batch, Teacher සහ Batch Mates විස්තරත් එක්කම Load කිරීම
        $student = Student::with(['batch.teacher', 'batch.students'])
                    ->where('email', $loggedUserEmail)
                    ->first();

        // Student Table එකේ Record එකක් නොතිබුණොත් Empty Data යැවීම
        if (!$student) {
            return view('student_portal.dashboard', [
                'student' => null,
                'batch' => null,
                'batchMates' => collect()
            ]);
        }

        $batch = $student->batch;
        // එකම Batch එකේ සිටින අනෙක් Students ලා (තමන් හැර)
        $batchMates = $batch ? $batch->students->where('id', '!=', $student->id) : collect();

        return view('student_portal.dashboard', compact('student', 'batch', 'batchMates'));
    }
}