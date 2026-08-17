<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\BatchController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\AuthController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Root URL එකට එන අයව Login Page එකට Redirect කිරීම
Route::get('/', function () {
    return redirect()->route('login');
});

Route::redirect('/home', '/admin/dashboard');

// --------------------------------------------------------------------------
// 1. Guest Routes (Login නොවුණු අය සඳහා පමණයි)
// --------------------------------------------------------------------------
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.store');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.store');
});

// --------------------------------------------------------------------------
// 2. Authenticated Routes (Login වූ අය සඳහා පමණයි)
// --------------------------------------------------------------------------
Route::middleware('auth')->group(function () {

    // Admin Dashboard
    Route::get('/admin/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Student Dashboard
    Route::get('/student-dashboard', function () {
        return view('student.dashboard');
    })->name('student.dashboard');

    // Teacher Dashboard
    Route::get('/teacher-dashboard', function () {
        return view('teacher.dashboard');
    })->name('teacher.dashboard');

    // Student Management
    Route::prefix('students')->group(function () {
        Route::get('/', [StudentController::class, 'index'])->name('student.index');
        Route::get('/create', [StudentController::class, 'create'])->name('student.register');
        Route::post('/store', [StudentController::class, 'store'])->name('student.store');
        Route::get('/{id}', [StudentController::class, 'show'])->name('student.show');
        Route::get('/{id}/edit', [StudentController::class, 'edit'])->name('student.edit');
        Route::put('/{id}', [StudentController::class, 'update'])->name('student.update');
        Route::delete('/{id}', [StudentController::class, 'destroy'])->name('student.destroy');
    });

    // Batch Management
    Route::prefix('batches')->group(function () {
        Route::get('/', [BatchController::class, 'index'])->name('batch.index');
        Route::post('/store', [BatchController::class, 'store'])->name('batch.store');
        Route::delete('/{id}', [BatchController::class, 'destroy'])->name('batch.destroy');
    });

    // Teacher Management
    Route::prefix('teachers')->group(function () {
        Route::get('/', [TeacherController::class, 'index'])->name('teacher.index');
        Route::get('/create', [TeacherController::class, 'create'])->name('teacher.create');
        Route::post('/store', [TeacherController::class, 'store'])->name('teacher.store');
        Route::get('/{id}', [TeacherController::class, 'show'])->name('teacher.show');
        Route::get('/{id}/edit', [TeacherController::class, 'edit'])->name('teacher.edit');
        Route::put('/{id}', [TeacherController::class, 'update'])->name('teacher.update');
        Route::delete('/{id}', [TeacherController::class, 'destroy'])->name('teacher.destroy');
    });

    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    
});
