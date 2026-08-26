<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Teacher;
use App\Models\Batch;
use App\Models\Exam;
use App\Models\ExamSubmission;

class TeacherPortalController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();

        // 1. Finding the Teacher associated with the Logged-in User's Email
        $teacher = Teacher::where('email', $user->email)->first();

        if (!$teacher) {
            return view('teacher.dashboard', [
                'batches' => collect(),
                'assignedBatchesCount' => 0,
                'totalStudentsCount' => 0,
                'activeModulesCount' => 0,
            ]);
        }

        // 2. Retrieve only Batches that match the relevant Teacher ID (with students count)
        $batches = Batch::where('teacher_id', $teacher->id)
            ->withCount('students')
            ->latest()
            ->get();

        $assignedBatchesCount = $batches->count();
        $totalStudentsCount = $batches->sum('students_count');

        // 3. Counting Exams created by Teacher using the 'created_by' column in the Exams table
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

    public function submissions()
    { 
        $user = Auth::user();
        $teacher = Teacher::where('email', $user->email)->first();

        // Teacher ගේ Exams ලබා ගැනීම (Export dropdown එක සඳහාද සමඟ)
        $teacherExams = Exam::where('created_by', $user->id)
            ->when($teacher, function ($query) use ($teacher) {
                return $query->orWhere('created_by', $teacher->id);
            })
            ->get();

        $teacherExamIds = $teacherExams->pluck('id');

        // Submissions Model Relationships සමඟ ලබා ගැනීම
        $submissions = ExamSubmission::with(['exam', 'student'])
            ->whereIn('exam_id', $teacherExamIds)
            ->latest('submitted_at')
            ->paginate(10);

        return view('teacher.submissions', compact('submissions', 'teacherExams'));
    }

    public function showSubmission($id)
    {
        $submission = ExamSubmission::with([
            'exam.questions.options',
            'student'
        ])->findOrFail($id);

        $rawAnswers = $submission->answers;
        $studentAnswers = [];

        if (is_string($rawAnswers)) {
            $studentAnswers = json_decode($rawAnswers, true) ?? [];
        } elseif (is_array($rawAnswers)) {
            $studentAnswers = $rawAnswers;
        }

        $totalScore = 0;
        $maxScore = 0;

        foreach ($submission->exam->questions as $index => $question) {
            $qMarks = $question->marks ?? 1;
            $maxScore += $qMarks;

            $userSelected = $studentAnswers[$question->id] 
                         ?? $studentAnswers[(string)$question->id] 
                         ?? $studentAnswers[$index] 
                         ?? null;

            if (is_array($userSelected)) {
                $userSelected = $userSelected['option_id'] ?? $userSelected['answer'] ?? $userSelected[0] ?? null;
            }

            $correctOption = $question->options->where('is_correct', true)->first();

            if ($correctOption && $userSelected !== null) {
                if ($userSelected == $correctOption->id || trim(strtolower((string)$userSelected)) === trim(strtolower((string)$correctOption->option_text))) {
                    $totalScore += $qMarks;
                }
            }
        }

        return view('teacher.submission_show', compact('submission', 'studentAnswers', 'totalScore', 'maxScore'));
    }

    public function gradeSubmission(Request $request, $id)
    {
        $submission = ExamSubmission::with(['exam.questions.options'])->findOrFail($id);
        $studentAnswers = is_string($submission->answers) ? json_decode($submission->answers, true) : ($submission->answers ?? []);

        $totalScore = 0;
        $maxScore = 0;

        foreach ($submission->exam->questions as $question) {
            $questionMarks = $question->marks ?? 1;
            $maxScore += $questionMarks;

            $selectedOptionId = $studentAnswers[$question->id] ?? $studentAnswers[(string)$question->id] ?? null;

            $correctOption = $question->options->where('is_correct', true)->first();

            if ($correctOption && ($selectedOptionId == $correctOption->id || $selectedOptionId == $correctOption->option_text)) {
                $totalScore += $questionMarks;
            }
        }

        $submission->update([
            'score' => $totalScore,
            'teacher_feedback' => $request->input('teacher_feedback') ?? $request->input('feedback'),
            'status' => 'graded',
            'graded_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Submission graded and feedback sent to student successfully!');
    }

    public function updateGrade(Request $request, $id)
    {
        $request->validate([
            'feedback' => 'nullable|string',
            'teacher_feedback' => 'nullable|string',
            'score' => 'nullable|numeric'
        ]);

        $submission = ExamSubmission::findOrFail($id);

        $submission->update([
            'score' => $request->has('score') ? $request->input('score') : $submission->score,
            'teacher_feedback' => $request->input('teacher_feedback') ?? $request->input('feedback'),
            'status' => 'graded',
        ]);

        return redirect()->back()->with('success', 'Submission graded and feedback sent successfully!');
    }

    // Export Pass/Fail Exam Report to CSV
    public function exportExamReport($examId)
   {
    // Questions and Student Submissions Load 
    $exam = Exam::with(['questions.options'])->findOrFail($examId);
    $submissions = ExamSubmission::with('student')
        ->where('exam_id', $examId)
        ->get();

    $fileName = 'Exam_Report_' . str_replace(' ', '_', $exam->title) . '_' . date('Y-m-d') . '.csv';

    $headers = [
        "Content-type"        => "text/csv",
        "Content-Disposition" => "attachment; filename=$fileName",
        "Pragma"              => "no-cache",
        "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
        "Expires"             => "0"
    ];

    $columns = ['Student ID', 'Student Name', 'Email', 'Correct Answers', 'Total Questions', 'Score Percentage', 'Status', 'Submitted At'];

    $callback = function() use ($submissions, $exam, $columns) {
        $file = fopen('php://output', 'w');
        fputcsv($file, $columns);

        // 1. Exam Total Max Score එ
        $totalExamMarks = 0;
        foreach ($exam->questions as $q) {
            $totalExamMarks += ($q->marks ?? 1);
        }

        // පnot problem soo Divide by zero 
        if ($totalExamMarks <= 0) {
            $totalExamMarks = count($exam->questions) > 0 ? count($exam->questions) : 1;
        }

        foreach ($submissions as $sub) {
            $earnedScore = 0;
            $studentAns = is_string($sub->answers) ? json_decode($sub->answers, true) : ($sub->answers ?? []);

            //  (Auto Calculate)
            foreach ($exam->questions as $index => $q) {
                $qMarks = $q->marks ?? 1;

                // Question ID or Index  Selected Option 
                $userAns = $studentAns[$q->id] 
                         ?? $studentAns[(string)$q->id] 
                         ?? $studentAns[$index] 
                         ?? null;

                if (is_array($userAns)) {
                    $userAns = $userAns['option_id'] ?? $userAns['answer'] ?? $userAns[0] ?? null;
                }

                $correctOption = $q->options->where('is_correct', true)->first();

                if ($correctOption && $userAns !== null) {
                    if ($userAns == $correctOption->id || trim(strtolower((string)$userAns)) === trim(strtolower((string)$correctOption->option_text))) {
                        $earnedScore += $qMarks;
                    }
                }
            }

            // DB  Score  Update  Calculate වූ Score 
            $finalScore = $sub->score !== null ? $sub->score : $earnedScore;

            // 3. Percentage (ex: 2/2  100%, 1/2  50%)
            $percentage = round(($finalScore / $totalExamMarks) * 100, 2);

            // 4. Pass / Fail Status  (50% over PASS, less than FAIL)
            $status = $percentage >= 50 ? 'PASS' : 'FAIL';

            fputcsv($file, [
                $sub->student->id ?? 'N/A',
                $sub->student->name ?? 'Unknown Student',
                $sub->student->email ?? 'N/A',
                $finalScore,
                $totalExamMarks,
                $percentage . '%',
                $status,
                $sub->submitted_at ? \Carbon\Carbon::parse($sub->submitted_at)->format('Y-m-d H:i') : 'N/A'
            ]);
        }

        fclose($file);
    };

    return response()->stream($callback, 200, $headers);
   }
}