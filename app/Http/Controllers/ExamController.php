<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Exam;
use App\Models\Teacher;
use App\Models\McqQuestion;
use App\Models\McqOption;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ExamController extends Controller
{
    public function index()
    {
        $exams = Exam::with('subject')
            ->where('created_by', Auth::id())
            ->latest()
            ->get();

        return view('teacher.exams.index', compact('exams'));
    }

    public function create()
    {
        $teacher = Teacher::where('email', Auth::user()->email)->first();
        $assignedSubjects = $teacher ? $teacher->subjects : collect();

        return view('teacher.exams.create', compact('assignedSubjects'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'subject_id'  => 'required|exists:course_subjects,id',
            'title'       => 'required|string|max:255',
            'type'        => 'required|in:mcq,assignment',
            'total_marks' => 'required|integer|min:1',
            'start_time'  => 'nullable|date',
            'end_time'    => 'nullable|date|after_or_equal:start_time',
        ]);

        $exam = Exam::create([
            'course_subject_id' => $request->subject_id,
            'created_by'        => Auth::id(),
            'title'             => $request->title,
            'instructions'      => $request->instructions,
            'type'              => $request->type,
            'start_time'        => $request->start_time,
            'end_time'          => $request->end_time,
            'total_marks'       => $request->total_marks,
            'is_published'      => $request->has('is_published') ? 1 : 0,
        ]);

        if ($exam->type === 'mcq') {
            return redirect()->route('teacher.exams.questions', $exam->id)
                             ->with('success', 'Exam created! Now add MCQ questions.');
        }

        return redirect()->route('teacher.exams.index')
                         ->with('success', 'Assignment created successfully!');
    }

    public function questions($id)
    {
        $exam = Exam::with(['questions.options'])->findOrFail($id);
        return view('teacher.exams.questions', compact('exam'));
    }

    public function storeQuestion(Request $request, $id)
    {
        $request->validate([
            'question'       => 'required|string',
            'options'        => 'required|array|min:2',
            'options.*'      => 'required|string',
            'correct_option' => 'required|integer',
            'marks'          => 'nullable|integer|min:1',
        ]);

        DB::transaction(function () use ($request, $id) {
            $question = McqQuestion::create([
                'exam_id'  => $id,
                'question' => $request->question,
                'marks'    => $request->marks ?? 1,
            ]);

            foreach ($request->options as $index => $optionText) {
                McqOption::create([
                    'mcq_question_id' => $question->id,
                    'option_text'     => $optionText,
                    'is_correct'      => ((int)$request->correct_option === $index),
                ]);
            }
        });

        return back()->with('success', 'Question added successfully!');
    }

    public function destroyQuestion($id)
    {
        $question = McqQuestion::findOrFail($id);
        $question->options()->delete();
        $question->delete();

        return back()->with('success', 'Question deleted successfully!');
    }

    public function togglePublish($id)
    {
        $exam = Exam::findOrFail($id);
        $exam->is_published = !$exam->is_published;
        $exam->save();

        return back()->with('success', 'Exam status updated successfully!');
    }

    public function destroy($id)
    {
        $exam = Exam::findOrFail($id);
        
        // Deleting associated questions and options
        foreach ($exam->questions as $question) {
            $question->options()->delete();
            $question->delete();
        }

        $exam->delete();

        return redirect()->route('teacher.exams.index')
                         ->with('success', 'Exam deleted successfully!');
    }
}