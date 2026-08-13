<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Batch;
use App\Models\Teacher;

class BatchController extends Controller
{
    // List all batches with student count and assigned teacher
    public function index()
    {
        $batches = Batch::with('teacher')->withCount('students')->latest()->get();
        $teachers = Teacher::all();

        return view('admin.batches.index', compact('batches', 'teachers'));
    }

    // Store new batch with assigned teacher
    public function store(Request $request)
    {
        $request->validate([
            'batch_name'  => 'required|string|max:255',
            'course_name' => 'required|string|max:255',
            'start_date'  => 'nullable|date',
            'teacher_id'  => 'nullable|exists:teachers,id',
        ]);

        Batch::create([
            'batch_name'  => $request->batch_name,
            'course_name' => $request->course_name,
            'start_date'  => $request->start_date,
            'teacher_id'  => $request->teacher_id,
        ]);

        return redirect()->back()->with('success', 'Batch created successfully!');
    }

    // Delete batch
    public function destroy($id)
    {
        Batch::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Batch deleted successfully!');
    }
}