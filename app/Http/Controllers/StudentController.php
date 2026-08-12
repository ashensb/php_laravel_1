<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\Batch;

class StudentController extends Controller
{
    // 1. Display all students with search & batch relationship
    public function index(Request $request)
    {
        // Search query parameter
        $search = $request->input('search');

        // Build search query for Reg No, Name, or Email
        $students = Student::with('batch')
            ->when($search, function ($query, $search) {
                return $query->where('reg_no', 'LIKE', "%{$search}%")
                             ->orWhere('name', 'LIKE', "%{$search}%")
                             ->orWhere('email', 'LIKE', "%{$search}%");
            })
            ->latest()
            ->get();

        // Correct view name: students_list (.blade.php)
        return view('students_list', compact('students', 'search'));
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
        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = ImageUpload::uploadImage($request->file('image'), 'Student/Profile');
        }

        Student::create([
            'reg_no'   => $request->reg_no,
            'name'     => $request->name,
            'email'    => $request->email,
            'dob'      => $request->dob,
            'age'      => $request->age,
            'password' => $request->password,
            'batch_id' => $request->batch_id,
            'img'      => $imagePath,
        ]);

        return redirect()->route('dashboard')->with('success', 'Student registered successfully!');
    }

    // 4. Load edit student page
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

        $data = [
            'reg_no'   => $request->reg_no,
            'name'     => $request->name,
            'email'    => $request->email,
            'dob'      => $request->dob,
            'age'      => $request->age,
            'password' => $request->password,
            'batch_id' => $request->batch_id,
        ];

        if ($request->hasFile('image')) {
            $data['img'] = ImageUpload::uploadImage($request->file('image'), 'Student/Profile');
        }

        $student->update($data);

        return redirect()->route('student.index')->with('success', 'Student updated successfully!');
    }

    // 6. Delete a student record
    public function destroy($id) 
    {
        $student = Student::findOrFail($id);
        $student->delete();

        return redirect()->route('student.index')->with('success', 'Student deleted successfully!');
    }

    // Student profile එක පෙන්වන function එක
     public function show($id)
    {
      // students and batch details load kirima..
     $student = Student::with('batch')->findOrFail($id);
    
     return view('show_student', compact('student'));
    }
}