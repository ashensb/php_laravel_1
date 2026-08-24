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
        // Dynamic Counts
        $totalStudents = Student::count();
        $totalTeachers = Teacher::count();
        $totalBatches = Batch::count();

        // Data for tables
        $recentStudents = Student::with('batch')->latest()->take(5)->get();
        $recentTeachers = Teacher::latest()->take(5)->get();

        // Chart Data (Check the column name of the batch table as 'name' or 'batch_name')
        $batches = Batch::withCount('students')->get();
        $batchNames = $batches->pluck('name'); 
        $studentCounts = $batches->pluck('students_count');

        return view('admin.dashboard', compact(
            'totalStudents', 
            'totalTeachers', 
            'totalBatches', 
            'recentStudents', 
            'recentTeachers',
            'batchNames',
            'studentCounts'
        ));
    }
}