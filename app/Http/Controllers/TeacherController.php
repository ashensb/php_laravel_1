<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Barryvdh\DomPDF\Facade\Pdf;

class TeacherController extends Controller
{
    // 1. All Teachers List with Search
    public function index(Request $request)
    {
        $search = $request->input('search');

        $teachers = Teacher::when($search, function ($query, $search) {
            return $query->where('name', 'LIKE', "%{$search}%")
                         ->orWhere('email', 'LIKE', "%{$search}%")
                         ->orWhere('qualification', 'LIKE', "%{$search}%");
        })
        ->latest()
        ->get();

        return view('admin.teachers.index', compact('teachers', 'search'));
    }

    // 2. Load Teacher Registration Form
    public function create()
    {
        return view('admin.teachers.create');
    }

    // 3. Store Teacher Data and Create Login User Account
    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:teachers,email|unique:users,email',
            'password' => 'required|string|min:6',
            'phone'    => 'nullable|string',
            'image'    => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('Teacher/Profile', 'public');
        }

        // adding Teachers Table 
        Teacher::create([
            'name'          => $request->name,
            'email'         => $request->email,
            'phone'         => $request->phone,
            'qualification' => $request->qualification,
            'img'           => $imagePath,
        ]);

        // Creating an account in the Users table so that the teacher can log in
        User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => 'teacher',
        ]);

        return redirect()->route('teacher.index')->with('success', 'Teacher registered and login account created successfully!');
    }

    // 4. Show Teacher Details Profile
    public function show($id)
    {
        $teacher = Teacher::findOrFail($id);
        return view('admin.teachers.show', compact('teacher'));
    }

    // 5. Edit Teacher Form
    public function edit($id)
    {
        $teacher = Teacher::findOrFail($id);
        return view('admin.teachers.edit', compact('teacher'));
    }

    // 6. Update Teacher Data
    public function update(Request $request, $id)
    {
        $teacher = Teacher::findOrFail($id);

        $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|unique:teachers,email,' . $id,
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = [
            'name'          => $request->name,
            'email'         => $request->email,
            'phone'         => $request->phone,
            'qualification' => $request->qualification,
        ];

        if ($request->hasFile('image')) {
            $data['img'] = $request->file('image')->store('Teacher/Profile', 'public');
        }

        // Updating the Teachers table
        $teacher->update($data);

        // If the email or name in the users table changes, update it as well.
        $user = User::where('email', $teacher->getOriginal('email'))->first();
        if ($user) {
            $user->update([
                'name'  => $request->name,
                'email' => $request->email,
            ]);
        }

        return redirect()->route('teacher.index')->with('success', 'Teacher details updated successfully!');
    }

    // 7. Delete Teacher
    public function destroy($id)
    {
        $teacher = Teacher::findOrFail($id);
        
        // Deleting the account in the Users table
        User::where('email', $teacher->email)->delete();

        // Deleting a record from the Teachers table
        $teacher->delete();

        return redirect()->route('teacher.index')->with('success', 'Teacher deleted successfully!');
    }

    // 8. Export filtered teachers to PDF
    public function exportPdf(Request $request)
    {
        $query = Teacher::query();

        // Filtering if there is a search query in the UI
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%")
                  ->orWhere('qualification', 'LIKE', "%{$search}%");
        }

        $teachers = $query->get();

        $pdf = Pdf::loadView('reports.teachers-pdf', compact('teachers'));
        return $pdf->download('teacher-list.pdf');
    }
}