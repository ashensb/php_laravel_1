<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

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

        // Teachers Table එකට එකතු කිරීම
        Teacher::create([
            'name'          => $request->name,
            'email'         => $request->email,
            'phone'         => $request->phone,
            'qualification' => $request->qualification,
            'img'           => $imagePath,
        ]);

        // Teacher ට Log විය හැකි පරිදි Users Table එකේ Account එකක් සෑදීම
        User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => 'teacher', // ඔබේ User Table එකේ role column එකක් ඇත්නම්
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

        // Teachers table එක Update කිරීම
        $teacher->update($data);

        // Users table එකේ Email හෝ Name වෙනස් වුවහොත් එයද Update කිරීම
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
        
        // Users Table එකේ ඇති Account එකද Delete කිරීම
        User::where('email', $teacher->email)->delete();

        // Teachers Table එකෙන් Record එක Delete කිරීම
        $teacher->delete();

        return redirect()->route('teacher.index')->with('success', 'Teacher deleted successfully!');
    }
}