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

        // 3.Counting Exams created by Teacher using the 'created_by' column in the Exams table
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

    // Teacher ගේ Exams වල ID ලබා ගැනීම
    $teacherExamIds = Exam::where('created_by', $user->id)
        ->when($teacher, function ($query) use ($teacher) {
            return $query->orWhere('created_by', $teacher->id);
        })
        ->pluck('id');

    // එලෙස සොයාගත් Exam IDs වලට අදාළ Submissions Model Relationships සමඟ ලබා ගැනීම
    $submissions = ExamSubmission::with(['exam', 'student'])
        ->whereIn('exam_id', $teacherExamIds)
        ->latest('submitted_at')
        ->paginate(10);

    return view('teacher.submissions', compact('submissions'));
   }

  public function showSubmission($id)
  {
    $submission = ExamSubmission::with([
        'exam.questions.options',
        'student'
    ])->findOrFail($id);

    // Answers Raw format එක Array එකක් බවට Decode කර ගැනීම
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

        // Key match: Question ID (int/string) හෝ Array Index
        $userSelected = $studentAnswers[$question->id] 
                     ?? $studentAnswers[(string)$question->id] 
                     ?? $studentAnswers[$index] 
                     ?? null;

        // Array ඇතුළේ තවත් level එකක් තිබේ නම් (e.g. ['option_id' => 5])
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

        // Correct answer check
        $correctOption = $question->options->where('is_correct', true)->first();

        if ($correctOption && ($selectedOptionId == $correctOption->id || $selectedOptionId == $correctOption->option_text)) {
            $totalScore += $questionMarks;
        }
    }

    $submission->update([
        'total_score' => $totalScore,
        'max_score' => $maxScore,
        'teacher_feedback' => $request->input('teacher_feedback'),
        'status' => 'graded',
        'graded_at' => now(),
    ]);

    return redirect()->back()->with('success', 'Submission graded and feedback sent to student successfully!');
   }

   public function updateGrade(Request $request, $id)
  {
    $request->validate([
        'feedback' => 'nullable|string',
        'marks' => 'nullable|numeric'
    ]);

    $submission = ExamSubmission::findOrFail($id);
    $submission->feedback = $request->feedback;
    
    // අවශ්‍ය නම් manual mark override එකක් ද ලබාදිය හැක
    if ($request->has('marks')) {
        $submission->marks_obtained = $request->marks;
    }
    
    $submission->status = 'graded'; // Status එක graded ලෙස update කිරීම
    $submission->save();

    return redirect()->route('teacher.submissions.index')
        ->with('success', 'Grade & Feedback published successfully!');
  }
}