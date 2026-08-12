<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\BatchController;
use App\Models\Student;
use App\Models\Batch;
use Carbon\Carbon;



// Dynamic Dashboard Route
Route::get('/', function () {
    // 1. Dynamic Counters from Database
    $totalStudents = Student::count();
    $totalBatches = Batch::count();
    
    // Total students registered in current month
    $newThisMonth = Student::whereMonth('created_at', Carbon::now()->month)
                           ->whereYear('created_at', Carbon::now()->year)
                           ->count();

    // 2. Fetch Recent 5 Registered Students with Batch details
    $recentStudents = Student::with('batch')->latest()->take(5)->get();

    // 3. Prepare Data for Analytics Chart (Batch Name vs Student Count)
    $batches = Batch::withCount('students')->get();
    $batchNames = $batches->pluck('batch_name');
    $studentCounts = $batches->pluck('students_count');

    return view('dashboard', compact(
        'totalStudents', 
        'totalBatches', 
        'newThisMonth', 
        'recentStudents', 
        'batchNames', 
        'studentCounts'
    ));
})->name('dashboard');


// Student Management Routes
Route::prefix('student')->group(function () {
    Route::get('/list', [StudentController::class, 'index'])->name('student.index');
    Route::get('/register', [StudentController::class, 'create'])->name('student.register');
    Route::post('/save', [StudentController::class, 'store'])->name('student.store');
    Route::get('/edit/{id}', [StudentController::class, 'edit'])->name('student.edit');
    Route::put('/update/{id}', [StudentController::class, 'update'])->name('student.update');
    Route::delete('/delete/{id}', [StudentController::class, 'destroy'])->name('student.destroy');
    Route::get('/student/view/{id}', [StudentController::class, 'show'])->name('student.show');
});


// Batch Management Routes
Route::prefix('batches')->group(function () {
    Route::get('/', [BatchController::class, 'index'])->name('batch.index');
    Route::post('/save', [BatchController::class, 'store'])->name('batch.store');
    Route::delete('/delete/{id}', [BatchController::class, 'destroy'])->name('batch.destroy');
});