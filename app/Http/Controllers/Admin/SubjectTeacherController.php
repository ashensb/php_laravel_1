<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Teacher;
use App\Models\Subject;
use Illuminate\Http\Request;

class SubjectTeacherController extends Controller
{
    // Assignment Page එක පෙන්වීම
   public function index()
   {
    $teachers = Teacher::with(['user', 'subjects'])->get();
    $subjects = Subject::all();

    return view('admin.subject_teacher.index', compact('teachers', 'subjects'));
   }  

    // Subject එකක් Teacher ට Assign කිරීම
    public function store(Request $request)
    {
        $request->validate([
            'teacher_id' => 'required|exists:teachers,id',
            'subject_id' => 'required|exists:course_subjects,id',
        ]);

        $teacher = Teacher::findOrFail($request->teacher_id);
        
        // එකම Subject එක දෙපාරක් Assign වීම වැළැක්වීමට (syncWithoutDetaching)
        $teacher->subjects()->syncWithoutDetaching([$request->subject_id]);

        return redirect()->back()->with('success', 'Subject assigned to teacher successfully!');
    }

    // Assigned Subject එකක් ඉවත් කිරීම (Remove)
    public function destroy($teacherId, $subjectId)
    {
        $teacher = Teacher::findOrFail($teacherId);
        $teacher->subjects()->detach($subjectId);

        return redirect()->back()->with('success', 'Subject removed from teacher successfully!');
    }
}