<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Batch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SubjectTeacherController extends Controller
{
    public function index()
    {
        // recive data only teacher table
        $teachers = DB::table('teachers')
            ->select('id', 'name', 'qualification')
            ->get();

        $courses = Batch::select('id', 'course_name')->get();

        // Getting details of assigned subjects
        foreach ($teachers as $teacher) {
            $teacher->assigned_subjects = DB::table('subject_teacher')
                ->join('course_subjects', 'subject_teacher.subject_id', '=', 'course_subjects.id')
                ->where('subject_teacher.teacher_id', $teacher->id) // using teacher_id 
                ->select('course_subjects.id', 'course_subjects.subject_name', 'course_subjects.subject_code')
                ->get();
        }

        return view('admin.subject_teacher.index', compact('teachers', 'courses'));
    }

    public function getSubjectsByCourse($courseId)
    {
        $subjects = DB::table('course_subjects')
            ->where('course_id', $courseId)
            ->select('id', 'subject_name', 'subject_code')
            ->get();

        return response()->json($subjects);
    }

    public function store(Request $request)
    {
        $request->validate([
            'teacher_id' => 'required',
            'subject_id' => 'required',
        ]);

        $exists = DB::table('subject_teacher')
            ->where('teacher_id', $request->teacher_id)
            ->where('subject_id', $request->subject_id)
            ->exists();

        if (!$exists) {
            DB::table('subject_teacher')->insert([
                'teacher_id' => $request->teacher_id,
                'subject_id' => $request->subject_id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            return redirect()->back()->with('success', 'Subject assigned to teacher successfully!');
        }

        return redirect()->back()->with('error', 'This subject is already assigned to the teacher!');
    }

    public function destroy($teacherId, $subjectId)
    {
        DB::table('subject_teacher')
            ->where('teacher_id', $teacherId)
            ->where('subject_id', $subjectId)
            ->delete();

        return redirect()->back()->with('success', 'Assignment removed successfully!');
    }
}