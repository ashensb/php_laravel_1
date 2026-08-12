<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Teacher;

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

        return view('teachers.index', compact('teachers', 'search'));
    }

    // 2. Load Teacher Registration Form
    public function create()
    {
        return view('teachers.create');
    }

    // 3. Store Teacher Data
    public function store(Request $request)
    {
        $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|unique:teachers,email',
            'phone' => 'nullable|string',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('Teacher/Profile', 'public');
        }

        Teacher::create([
            'name'          => $request->name,
            'email'         => $request->email,
            'phone'         => $request->phone,
            'qualification' => $request->qualification,
            'img'           => $imagePath,
        ]);

        return redirect()->route('teacher.index')->with('success', 'Teacher registered successfully!');
    }

    // 4. Show Teacher Details Profile
    public function show($id)
    {
        $teacher = Teacher::findOrFail($id);
        return view('teachers.show', compact('teacher'));
    }

    // 5. Edit Teacher Form
    public function edit($id)
    {
        $teacher = Teacher::findOrFail($id);
        return view('teachers.edit', compact('teacher'));
    }

    // 6. Update Teacher Data
    public function update(Request $request, $id)
    {
        $teacher = Teacher::findOrFail($id);

        $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|unique:teachers,email,' . $id,
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

        $teacher->update($data);

        return redirect()->route('teacher.index')->with('success', 'Teacher details updated successfully!');
    }

    // 7. Delete Teacher
    public function destroy($id)
    {
        $teacher = Teacher::findOrFail($id);
        $teacher->delete();

        return redirect()->route('teacher.index')->with('success', 'Teacher deleted successfully!');
    }
}