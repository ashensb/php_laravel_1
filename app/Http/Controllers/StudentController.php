<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\Batch;

class StudentController extends Controller
{
    // 1. Display all students with their assigned batch
    public function index() 
    {
        // Fetch students along with batch relationship data
        $students = Student::with('batch')->get();
        return view('students_list', compact('students'));
    }

    // 2. Load student registration form with batches dropdown
    public function create() 
    {
        $batches = Batch::all();
        return view("create", compact('batches'));
    }

    // 3. Store new student details
    public function store(Request $request) 
    {
        Student::create([
            'reg_no'   => $request->reg_no,
            'name'     => $request->name,
            'email'    => $request->email,
            'dob'      => $request->dob,
            'age'      => $request->age,
            'password' => $request->password,
            'batch_id' => $request->batch_id,
        ]);

        return redirect()->route('dashboard')->with('success', 'Student registered successfully!');
    }

    // 4. Load edit student page with student data and batches list
    public function edit($id) 
    {
        $student = Student::findOrFail($id);
        $batches = Batch::all();
        return view('edit_student', compact('student', 'batches'));
    }

    // 5. Update existing student details
    public function update(Request $request, $id) 
    {
        $student = Student::findOrFail($id);
        $student->update([
            'reg_no'   => $request->reg_no,
            'name'     => $request->name,
            'email'    => $request->email,
            'dob'      => $request->dob,
            'age'      => $request->age,
            'password' => $request->password,
            'batch_id' => $request->batch_id,
        ]);

        return redirect()->route('student.index')->with('success', 'Student updated successfully!');
    }

    // 6. Delete a student record
    public function destroy($id) 
    {
        $student = Student::findOrFail($id);
        $student->delete();

        return redirect()->route('student.index')->with('success', 'Student deleted successfully!');
    }
}