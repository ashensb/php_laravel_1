<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Student;
use App\Models\Exam;
use Illuminate\Support\Facades\DB;

class StudentPortalController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // 1. Logged-in student profile
        $student = Student::with('batch')->where('email', $user->email)->first();

        if (!$student) {
            return view('student_portal.dashboard', [
                'student' => null,
                'batch' => null,
                'exams' => collect(),
                'batchMates' => collect()
            ]);
        }

        $batch = $student->batch;
        $exams = collect();

        if ($batch) {
            // 2. Batch එකේ course_id එකෙන් Direct filter කිරීම
            // (ඔබේ batch table එකේ course ID එක සඳහන් වන්නේ course_id ලෙසද නැතහොත් id ලෙසද යන්න අනුව ($batch->course_id ?? $batch->id) යොදන්න)
            $targetCourseId = $batch->course_id ?? $batch->id;

            $courseSubjectIds = DB::table('course_subjects')
                ->where('course_id', $targetCourseId)
                ->pluck('id')
                ->toArray();

            // 3. Published Exams Fetch කිරීම
            if (!empty($courseSubjectIds)) {
                $exams = Exam::whereIn('course_subject_id', $courseSubjectIds)
                    ->where('is_published', 1)
                    ->get();
            }
        }

        // Batch Mates
        $batchMates = Student::where('batch_id', $student->batch_id)
            ->where('id', '!=', $student->id)
            ->get();

        return view('student_portal.dashboard', compact('student', 'batch', 'exams', 'batchMates'));
    }
}