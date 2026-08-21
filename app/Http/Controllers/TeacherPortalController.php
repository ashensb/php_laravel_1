<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Teacher;
use App\Models\Batch;
use App\Models\Exam;

class TeacherPortalController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();

        // 1. Logged-in User ගේ Email එකට අදාළ Teacher සොයාගැනීම
        $teacher = Teacher::where('email', $user->email)->first();

        if (!$teacher) {
            return view('teacher.dashboard', [
                'batches' => collect(),
                'assignedBatchesCount' => 0,
                'totalStudentsCount' => 0,
                'activeModulesCount' => 0,
            ]);
        }

        // 2. අදාළ Teacher ID එකට සමාන Batches පමණක් ලබාගැනීම (students count සමඟ)
        $batches = Batch::where('teacher_id', $teacher->id)
            ->withCount('students')
            ->latest()
            ->get();

        $assignedBatchesCount = $batches->count();
        $totalStudentsCount = $batches->sum('students_count');

        // 3. Exams table එකේ 'created_by' column එක මගින් Teacher සෑදූ Exams count කිරීම
        $activeModulesCount = Exam::where('created_by', $user->id)
            ->orWhere('created_by', $teacher->id)
            ->count();

        return view('teacher.dashboard', compact(
            'batches', 
            'assignedBatchesCount', 
            'totalStudentsCount', 
            'activeModulesCount'
        ));
    }
}