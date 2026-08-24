<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Batch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SubjectController extends Controller
{
    public function index()
    {
        // Batch Table recive courses
        $courses = Batch::select('id', 'course_name')->get();

        // recive subject and coursess through database
        $subjects = DB::table('course_subjects')
            ->join('batches', 'course_subjects.course_id', '=', 'batches.id')
            ->select('course_subjects.*', 'batches.course_name')
            ->get();

        return view('admin.subjects.index', compact('courses', 'subjects'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'course_id' => 'required',
            'subject_code' => 'required',
            'subject_name' => 'required',
        ]);

        DB::table('course_subjects')->insert([
            'course_id'    => $request->course_id,
            'subject_code' => $request->subject_code,
            'subject_name' => $request->subject_name,
            'created_at'   => now(),
            'updated_at'   => now(),
        ]);

        return redirect()->back()->with('success', 'Subject created successfully under the course!');
    }

    public function destroy($id)
    {
        DB::table('course_subjects')->where('id', $id)->delete();
        return redirect()->back()->with('success', 'Subject deleted successfully!');
    }
}