<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\BatchController;
use App\Http\Controllers\TeacherController;

// Dashboard Route (Using DashboardController)
Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

// Student Management Routes
Route::prefix('student')->group(function () {
    Route::get('/list', [StudentController::class, 'index'])->name('student.index');
    Route::get('/register', [StudentController::class, 'create'])->name('student.register');
    Route::post('/save', [StudentController::class, 'store'])->name('student.store');
    Route::get('/edit/{id}', [StudentController::class, 'edit'])->name('student.edit');
    Route::put('/update/{id}', [StudentController::class, 'update'])->name('student.update');
    Route::delete('/delete/{id}', [StudentController::class, 'destroy'])->name('student.destroy');
    Route::get('/view/{id}', [StudentController::class, 'show'])->name('student.show');
});

// Batch Management Routes
Route::prefix('batches')->group(function () {
    Route::get('/', [BatchController::class, 'index'])->name('batch.index');
    Route::post('/save', [BatchController::class, 'store'])->name('batch.store');
    Route::delete('/delete/{id}', [BatchController::class, 'destroy'])->name('batch.destroy');
});

// Teacher Management Routes
Route::prefix('teachers')->group(function () {
    Route::get('/', [TeacherController::class, 'index'])->name('teacher.index');
    Route::get('/create', [TeacherController::class, 'create'])->name('teacher.create');
    Route::post('/store', [TeacherController::class, 'store'])->name('teacher.store');
    Route::get('/{id}', [TeacherController::class, 'show'])->name('teacher.show');
    Route::get('/{id}/edit', [TeacherController::class, 'edit'])->name('teacher.edit');
    Route::put('/{id}', [TeacherController::class, 'update'])->name('teacher.update');
    Route::delete('/{id}', [TeacherController::class, 'destroy'])->name('teacher.destroy');
});