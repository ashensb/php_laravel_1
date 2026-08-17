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

        // Tables සඳහා Data
        $recentStudents = Student::with('batch')->latest()->take(5)->get();
        $recentTeachers = Teacher::latest()->take(5)->get();

        // Chart Data (Batch table එකේ column name එක 'name' හෝ 'batch_name' අනුව පරීක්ෂා කරගන්න)
        $batches = Batch::withCount('students')->get();
        $batchNames = $batches->pluck('name'); // ඔබේ DB එකේ තියෙන්නෙ 'batch_name' නම් මෙතන 'batch_name' ලෙස වෙනස් කරන්න
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