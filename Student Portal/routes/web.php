<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ResultController;
use App\Http\Controllers\StudentController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes - Student Result Portal
|--------------------------------------------------------------------------
*/

// Home redirects to student list
Route::get('/', function () {
    return redirect()->route('students.index');
});

// Authentication Routes (Guest Only)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

// Logout Route (Auth Only)
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

/*
|--------------------------------------------------------------------------
| Student & Result Routes
|--------------------------------------------------------------------------
|
| - Notice: /students/create is registered BEFORE /students/{student}
|   to prevent "create" being interpreted as a student ID!
|
*/

// Public Route: Students List / Directory
Route::get('/students', [StudentController::class, 'index'])->name('students.index');

// Protected Routes: Create & Store (Auth Middleware)
Route::middleware(['auth'])->group(function () {
    Route::get('/students/create', [StudentController::class, 'create'])->name('students.create');
    Route::post('/students', [StudentController::class, 'store'])->name('students.store');
});

// Public Route: View Student Profile & Automated Results Page
Route::get('/students/{student}', [StudentController::class, 'show'])->name('students.show');

// Protected Routes: Edit, Update, Delete & Result Modifications
Route::middleware(['auth'])->group(function () {
    Route::get('/students/{student}/edit', [StudentController::class, 'edit'])->name('students.edit');
    Route::put('/students/{student}', [StudentController::class, 'update'])->name('students.update');
    Route::delete('/students/{student}', [StudentController::class, 'destroy'])->name('students.destroy');

    // Add and Remove Results (Calculates grade automatically)
    Route::post('/students/{student}/results', [ResultController::class, 'store'])->name('results.store');
    Route::delete('/students/{student}/results/{result}', [ResultController::class, 'destroy'])->name('results.destroy');
});
