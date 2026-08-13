<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\Batch;
use App\Models\Teacher;

class DashboardController extends Controller
{
    public function index()
    {
        $totalStudents = Student::count();
        $totalBatches  = Batch::count();
        $totalTeachers = Teacher::count();

        $newThisMonth  = Student::whereMonth('created_at', now()->month)
                                ->whereYear('created_at', now()->year)
                                ->count();

        $recentStudents = Student::with('batch')->latest()->take(5)->get();
        $recentTeachers = Teacher::latest()->take(5)->get();

        // Chart Data
        $batches = Batch::withCount('students')->get();
        $batchNames = $batches->pluck('batch_name');
        $studentCounts = $batches->pluck('students_count');

        return view('admin.dashboard', compact(
            'totalStudents', 
            'totalBatches', 
            'totalTeachers', 
            'newThisMonth', 
            'recentStudents', 
            'recentTeachers', 
            'batchNames', 
            'studentCounts'
        ));
    }
}