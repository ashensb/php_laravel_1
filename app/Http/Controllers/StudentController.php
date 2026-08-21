<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\Batch;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class StudentController extends Controller
{
    // 1. Display all students with search & batch relationship
    public function index(Request $request)
    {
        $search = $request->input('search');

        $students = Student::with('batch')
            ->when($search, function ($query, $search) {
                return $query->where('reg_no', 'LIKE', "%{$search}%")
                             ->orWhere('name', 'LIKE', "%{$search}%")
                             ->orWhere('email', 'LIKE', "%{$search}%");
            })
            ->latest()
            ->get();

        return view('admin.students.index', compact('students', 'search'));
    }

    // 2. Load student registration form with batches dropdown
    public function create() 
    {
        $batches = Batch::all();
        return view('admin.students.create', compact('batches'));
    }

    // 3. Store new student details & create login user account
    public function store(Request $request) 
    {
        // Validation Rules
        $request->validate([
            'reg_no'    => 'required|unique:students,reg_no',
            'name'      => 'required|string|max:255',
            'email'     => 'required|email|unique:users,email|unique:students,email',
            'password'  => 'required|min:4',
            'batch_id'  => 'required|exists:batches,id',
            'image'     => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ], [
            'email.unique'  => 'මෙම Email ලිපිනය දැනටමත් පද්ධතියේ ලියාපදිංචි කර ඇත.',
            'reg_no.unique' => 'මෙම Reg No එක දැනටමත් පද්ධතියේ ඇත.',
        ]);

        $imagePath = null;
        
        // Custom Image Upload Logic
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('storage/Student/Profile'), $fileName);
            $imagePath = 'Student/Profile/' . $fileName;
        }

        // Batch එක හරහා අදාළ course_id එක ගෙන ඒම
        $batch = Batch::find($request->batch_id);
        $courseId = $batch ? $batch->course_id : null;

        // DB Transaction මගින් User සහ Student create කිරීම
        DB::transaction(function () use ($request, $imagePath, $courseId) {
            User::create([
                'name'     => $request->name,
                'email'    => $request->email,
                'password' => Hash::make($request->password),
                'role'     => 'student',
            ]);

            Student::create([
                'reg_no'    => $request->reg_no,
                'name'      => $request->name,
                'email'     => $request->email,
                'dob'       => $request->dob,
                'age'       => $request->age,
                'password'  => $request->password,
                'batch_id'  => $request->batch_id,
                'course_id' => $courseId, // course_id එක මෙතනදී save වේ
                'img'       => $imagePath,
            ]);
        });

        return redirect()->route('student.index')->with('success', 'Student registered and User account created successfully!');
    }

    // 4. Load edit student page
    public function edit($id) 
    {
        $student = Student::findOrFail($id);
        $batches = Batch::all();
        return view('admin.students.edit', compact('student', 'batches'));
    }

    // 5. Update existing student details & update user account email
    public function update(Request $request, $id) 
    {
        $student = Student::findOrFail($id);

        $batch = Batch::find($request->batch_id);
        $courseId = $batch ? $batch->course_id : null;

        $data = [
            'reg_no'    => $request->reg_no,
            'name'      => $request->name,
            'email'     => $request->email,
            'dob'       => $request->dob,
            'age'       => $request->age,
            'password'  => $request->password,
            'batch_id'  => $request->batch_id,
            'course_id' => $courseId,
        ];

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('storage/Student/Profile'), $fileName);
            $data['img'] = 'Student/Profile/' . $fileName;
        }

        $user = User::where('email', $student->email)->first();
        if ($user) {
            $userData = [
                'name'  => $request->name,
                'email' => $request->email,
            ];
            
            if ($request->filled('password')) {
                $userData['password'] = Hash::make($request->password);
            }

            $user->update($userData);
        }

        $student->update($data);

        return redirect()->route('student.index')->with('success', 'Student updated successfully!');
    }

    // 6. Delete a student record & user account
    public function destroy($id) 
    {
        $student = Student::findOrFail($id);

        $user = User::where('email', $student->email)->first();
        if ($user) {
            $user->delete();
        }

        $student->delete();

        return redirect()->route('student.index')->with('success', 'Student deleted successfully!');
    }

    // 7. Student profile show function
    public function show($id)
    {
        $student = Student::with('batch')->findOrFail($id);
        return view('admin.students.show', compact('student'));
    }
}