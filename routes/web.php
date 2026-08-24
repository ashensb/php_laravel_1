<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\BatchController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\StudentPortalController;
use App\Http\Controllers\Admin\SubjectTeacherController;
use App\Http\Controllers\Admin\SubjectController;
use App\Http\Controllers\ExamController;
use App\Http\Controllers\TeacherPortalController;
use App\Http\Middleware\RoleMiddleware;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Root URL Redirections
Route::get('/', function () {
    return redirect()->route('login');
});

Route::redirect('/home', '/admin/dashboard');

// --------------------------------------------------------------------------
// 1. Guest Routes
// --------------------------------------------------------------------------
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.store');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.store');
});

// --------------------------------------------------------------------------
// 2. Authenticated General Routes
// --------------------------------------------------------------------------
Route::middleware('auth')->group(function () {

    // Dashboards
    Route::get('/admin/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Fixed: Folder name corrected to student_portal.dashboard
    Route::get('/student-dashboard', [StudentPortalController::class, 'index'])->name('student.dashboard');


    // Student Management
    Route::prefix('students')->group(function () {
        Route::get('/', [StudentController::class, 'index'])->name('student.index');
        Route::get('/export-pdf', [StudentController::class, 'exportPdf'])->name('students.export-pdf');
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
        Route::get('/export-pdf', [TeacherController::class, 'exportPdf'])->name('teachers.export-pdf');
        Route::get('/create', [TeacherController::class, 'create'])->name('teacher.create');
        Route::post('/store', [TeacherController::class, 'store'])->name('teacher.store');
        Route::get('/{id}', [TeacherController::class, 'show'])->name('teacher.show');
        Route::get('/{id}/edit', [TeacherController::class, 'edit'])->name('teacher.edit');
        Route::put('/{id}', [TeacherController::class, 'update'])->name('teacher.update');
        Route::delete('/{id}', [TeacherController::class, 'destroy'])->name('teacher.destroy');
    });

    // Student Portal & Exam Routes
  Route::prefix('student')->name('student.')->group(function () {
    Route::get('/dashboard', [StudentPortalController::class, 'index'])->name('dashboard');
    Route::get('/portal', [StudentPortalController::class, 'index'])->name('portal');
    Route::get('/exam/{id}', [StudentPortalController::class, 'showExam'])->name('exam.show');
    Route::post('/exam/{examId}/submit', [StudentPortalController::class, 'submitExam'])->name('exam.submit');
    Route::get('/exam/{id}/result', [StudentPortalController::class, 'viewResult'])->name('exam.result');
   });

    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

// --------------------------------------------------------------------------
// 3. Admin Protected Routes
// --------------------------------------------------------------------------
Route::prefix('admin')->middleware(['auth'])->group(function () {
    
    // Admin Only Routes
    Route::middleware([RoleMiddleware::class . ':admin'])->group(function () {
        Route::get('/subject-teacher', [SubjectTeacherController::class, 'index'])->name('admin.subject-teacher.index');
        Route::post('/subject-teacher', [SubjectTeacherController::class, 'store'])->name('admin.subject-teacher.store');
        Route::delete('/subject-teacher/{teacherId}/{subjectId}', [SubjectTeacherController::class, 'destroy'])->name('admin.subject-teacher.destroy');
    });

    // Subject Routes
    Route::get('/subjects', [SubjectController::class, 'index'])->name('admin.subjects.index');
    Route::post('/subjects', [SubjectController::class, 'store'])->name('admin.subjects.store');
    Route::delete('/subjects/{id}', [SubjectController::class, 'destroy'])->name('admin.subjects.destroy');

    // Course Subjects AJAX Route
    Route::get('/get-subjects-by-course/{courseId}', [SubjectTeacherController::class, 'getSubjectsByCourse']);
});

// --------------------------------------------------------------------------
// 4. Teacher Protected Routes
// --------------------------------------------------------------------------
Route::middleware(['auth'])->prefix('teacher')->name('teacher.')->group(function () {
    Route::get('/dashboard', [TeacherPortalController::class, 'dashboard'])->name('dashboard');
    Route::get('/exams', [ExamController::class, 'index'])->name('exams.index');
    Route::get('/exams/create', [ExamController::class, 'create'])->name('exams.create');
    Route::post('/exams', [ExamController::class, 'store'])->name('exams.store');
    Route::get('/submissions', [TeacherPortalController::class, 'submissions'])->name('submissions');
    Route::get('/submissions/{id}', [TeacherPortalController::class, 'showSubmission'])->name('submissions.show');
    Route::post('/submissions/{id}/grade', [TeacherPortalController::class, 'gradeSubmission'])->name('submissions.grade');
    Route::put('/submissions/{id}', [TeacherController::class, 'updateGrade'])->name('submissions.update');

    // MCQ Questions Management
    Route::get('/exams/{id}/questions', [ExamController::class, 'questions'])->name('exams.questions');
    Route::post('/exams/{id}/questions', [ExamController::class, 'storeQuestion'])->name('exams.questions.store');
    Route::delete('/questions/{id}', [ExamController::class, 'destroyQuestion'])->name('exams.questions.destroy');

    // Exam Management
    Route::patch('/exams/{id}/toggle-publish', [ExamController::class, 'togglePublish'])->name('exams.toggle-publish');
    Route::delete('/exams/{id}', [ExamController::class, 'destroy'])->name('exams.destroy');
});

