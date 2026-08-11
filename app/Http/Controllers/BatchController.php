<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Batch;

class BatchController extends Controller
{
    // List all batches
    public function index()
    {
        $batches = Batch::withCount('students')->get(); // Count students in each batch
        return view('batches.index', compact('batches'));
    }

    // Store new batch
    public function store(Request $request)
    {
        $request->validate([
            'batch_name' => 'required',
            'course_name' => 'required'
        ]);

        Batch::create([
            'batch_name' => $request->batch_name,
            'course_name' => $request->course_name,
            'start_date' => $request->start_date
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