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
    /**
     * Teacher Dashboard with Dynamic Score Calculation for Analytics Charts
     */
    public function dashboard()
    {
        $user = Auth::user();
        $teacher = Teacher::where('email', $user->email)->first();

        if (!$teacher) {
            return view('teacher.dashboard', [
                'batches' => collect(),
                'assignedBatchesCount' => 0,
                'totalStudentsCount' => 0,
                'activeModulesCount' => 0,
                'totalPassed' => 0,
                'totalFailed' => 0,
                'chartLabels' => [],
                'chartPassData' => [],
            ]);
        }

        $batches = Batch::where('teacher_id', $teacher->id)
            ->withCount('students')
            ->latest()
            ->get();

        $assignedBatchesCount = $batches->count();
        $totalStudentsCount = $batches->sum('students_count');

        $exams = Exam::where('created_by', $user->id)
            ->orWhere('created_by', $teacher->id)
            ->with(['questions.options', 'submissions'])
            ->get();

        $activeModulesCount = $exams->count();

        $totalPassed = 0;
        $totalFailed = 0;
        $chartLabels = [];
        $chartPassData = [];

        foreach ($exams as $exam) {
            $examTotalMarks = 0;
            foreach ($exam->questions as $q) {
                $examTotalMarks += ($q->marks ?? 1);
            }

            if ($examTotalMarks <= 0) {
                $examTotalMarks = count($exam->questions) > 0 ? count($exam->questions) : 1;
            }

            $examPassCount = 0;
            $submissionsCount = $exam->submissions->count();

            foreach ($exam->submissions as $sub) {
                if ($sub->score !== null) {
                    $finalScore = $sub->score;
                } else {
                    $earnedScore = 0;
                    $studentAns = is_string($sub->answers) ? json_decode($sub->answers, true) : ($sub->answers ?? []);

                    foreach ($exam->questions as $index => $q) {
                        $qMarks = $q->marks ?? 1;

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
                    $finalScore = $earnedScore;
                }

                $percentage = ($finalScore / $examTotalMarks) * 100;

                if ($percentage >= 50) {
                    $totalPassed++;
                    $examPassCount++;
                } else {
                    $totalFailed++;
                }
            }

            if ($submissionsCount > 0) {
                $chartLabels[] = $exam->title;
                $chartPassData[] = round(($examPassCount / $submissionsCount) * 100, 1);
            }
        }

        return view('teacher.dashboard', compact(
            'batches', 
            'assignedBatchesCount', 
            'totalStudentsCount', 
            'activeModulesCount',
            'totalPassed',
            'totalFailed',
            'chartLabels',
            'chartPassData'
        ));
    }

    /**
     * View Submissions List with Optional Exam Filter
     */
    public function submissions(Request $request)
    { 
        $user = Auth::user();
        $teacher = Teacher::where('email', $user->email)->first();

        $teacherExams = Exam::where('created_by', $user->id)
            ->when($teacher, function ($query) use ($teacher) {
                return $query->orWhere('created_by', $teacher->id);
            })
            ->get();

        $teacherExamIds = $teacherExams->pluck('id');
        $selectedExamId = $request->query('exam_id');

        $query = ExamSubmission::with(['exam.questions.options', 'student'])
            ->whereIn('exam_id', $teacherExamIds);

        if ($selectedExamId) {
            $query->where('exam_id', $selectedExamId);
        }

        $submissions = $query->latest('submitted_at')->paginate(10);

        foreach ($submissions as $sub) {
            if ($sub->score === null && $sub->exam) {
                $earnedScore = 0;
                $studentAns = is_string($sub->answers) ? json_decode($sub->answers, true) : ($sub->answers ?? []);

                foreach ($sub->exam->questions as $index => $q) {
                    $qMarks = $q->marks ?? 1;
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
                $sub->calculated_score = $earnedScore;
            } else {
                $sub->calculated_score = $sub->score;
            }
        }

        return view('teacher.submissions', compact('submissions', 'teacherExams', 'selectedExamId'));
    }

    /**
     * Show Detailed Single Submission Page
     */
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

    /**
     * First-time Grade Submission & Save Feedback
     */
    public function gradeSubmission(Request $request, $id)
    {
        $submission = ExamSubmission::with(['exam.questions.options'])->findOrFail($id);
        $studentAnswers = is_string($submission->answers) ? json_decode($submission->answers, true) : ($submission->answers ?? []);

        $totalScore = 0;

        foreach ($submission->exam->questions as $index => $question) {
            $questionMarks = $question->marks ?? 1;

            $selectedOptionId = $studentAnswers[$question->id] 
                              ?? $studentAnswers[(string)$question->id] 
                              ?? $studentAnswers[$index] 
                              ?? null;

            if (is_array($selectedOptionId)) {
                $selectedOptionId = $selectedOptionId['option_id'] ?? $selectedOptionId['answer'] ?? $selectedOptionId[0] ?? null;
            }

            $correctOption = $question->options->where('is_correct', true)->first();

            if ($correctOption && $selectedOptionId !== null) {
                if ($selectedOptionId == $correctOption->id || trim(strtolower((string)$selectedOptionId)) === trim(strtolower((string)$correctOption->option_text))) {
                    $totalScore += $questionMarks;
                }
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

    /**
     * Update Existing Grade / Manual Overwrite
     */
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

    /**
     * Export Pass/Fail Exam Report to CSV (Supports Single Exam or All Exams)
     */
    public function exportExamReport(Request $request, $examId = null)
    {
        $user = Auth::user();
        $teacher = Teacher::where('email', $user->email)->first();

        $examId = $examId ?? $request->query('exam_id');

        // Fetch valid Exams owned by the teacher
        $teacherExams = Exam::where('created_by', $user->id)
            ->when($teacher, function ($query) use ($teacher) {
                return $query->orWhere('created_by', $teacher->id);
            })
            ->pluck('id');

        $query = ExamSubmission::with(['student', 'exam.questions.options'])
            ->whereIn('exam_id', $teacherExams);

        if ($examId) {
            $query->where('exam_id', $examId);
            $examTitle = Exam::where('id', $examId)->value('title') ?? 'Exam';
            $fileName = 'Exam_Report_' . str_replace(' ', '_', $examTitle) . '_' . date('Y-m-d') . '.csv';
        } else {
            $fileName = 'All_Exams_Report_' . date('Y-m-d') . '.csv';
        }

        $submissions = $query->get();

        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $columns = ['Student ID', 'Student Name', 'Email', 'Exam Title', 'Correct Answers', 'Total Questions', 'Score Percentage', 'Status', 'Submitted At'];

        $callback = function() use ($submissions, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($submissions as $sub) {
                $exam = $sub->exam;
                if (!$exam) continue;

                $totalExamMarks = 0;
                foreach ($exam->questions as $q) {
                    $totalExamMarks += ($q->marks ?? 1);
                }

                if ($totalExamMarks <= 0) {
                    $totalExamMarks = count($exam->questions) > 0 ? count($exam->questions) : 1;
                }

                $earnedScore = 0;
                $studentAns = is_string($sub->answers) ? json_decode($sub->answers, true) : ($sub->answers ?? []);

                foreach ($exam->questions as $index => $q) {
                    $qMarks = $q->marks ?? 1;

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

                $finalScore = $sub->score !== null ? $sub->score : $earnedScore;
                $percentage = round(($finalScore / $totalExamMarks) * 100, 2);
                $status = $percentage >= 50 ? 'PASS' : 'FAIL';

                fputcsv($file, [
                    $sub->student->id ?? 'N/A',
                    $sub->student->name ?? 'Unknown Student',
                    $sub->student->email ?? 'N/A',
                    $exam->title ?? 'N/A',
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