<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\Batch;
use App\Models\Exam;
use App\Models\ExamSubmission;
use Illuminate\Support\Facades\Auth;

class StudentPortalController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $student = Student::where('email', $user->email)->first();

        if (!$student) {
            return view('student_portal.dashboard', [
                'student' => null,
                'batch' => null,
                'exams' => collect(),
                'submissions' => [],
                'batchMates' => collect()
            ]);
        }

        $batch = $student->batch;

        // BATCH FILTER එක සහ PUBLISHED CHECK එක රිලැක්ස් කර සියලුම EXAMS FETCH කිරීම (Debug Mode)
        // ඔබගේ DATABASE එකේ EXAM එකෙහි batch_id එක වෙනස් වුවද මෙය හරහා පෙන්නුම් කරයි
        $exams = Exam::where('batch_id', $student->batch_id)
            ->orWhereNull('batch_id') // batch_id assign කර නැතිනම්
            ->latest()
            ->get();

        // Student ගේ Submissions Fetch කිරීම
        $submissions = ExamSubmission::where('student_id', $student->id)
            ->get()
            ->keyBy('exam_id');

        // Batch mates
        $batchMates = Student::where('batch_id', $student->batch_id)
            ->where('id', '!=', $student->id)
            ->get();

        return view('student_portal.dashboard', compact('student', 'batch', 'exams', 'submissions', 'batchMates'));
    }

    public function showExam($id)
    {
        $user = Auth::user();
        $student = Student::where('email', $user->email)->first();

        if (!$student) {
            return redirect()->route('student.dashboard')
                ->with('error', 'Student profile record not found.');
        }

        $existingSubmission = ExamSubmission::where('exam_id', $id)
            ->where('student_id', $student->id)
            ->first();

        if ($existingSubmission) {
            return redirect()->route('student.dashboard')
                ->with('error', 'You have already completed this exam!');
        }

        $exam = Exam::with('questions.options')->findOrFail($id);
        return view('student_portal.exam_show', compact('exam'));
    }

    public function submitExam(Request $request, $examId)
    {
        $user = Auth::user();
        $student = Student::where('email', $user->email)->first();

        if (!$student) {
            return redirect()->route('student.dashboard')
                ->with('error', 'Student profile record not found.');
        }

        $existingSubmission = ExamSubmission::where('exam_id', $examId)
            ->where('student_id', $student->id)
            ->first();

        if ($existingSubmission) {
            return redirect()->route('student.dashboard')
                ->with('error', 'You have already submitted this exam!');
        }

        ExamSubmission::create([
            'exam_id' => $examId,
            'student_id' => $student->id,
            'answers' => json_encode($request->input('answers', [])),
            'submitted_at' => now(),
            'status' => 'pending',
        ]);

        return redirect()->route('student.dashboard')
            ->with('success', 'Exam submitted successfully!');
    }
}