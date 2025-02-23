<?php

use App\Http\Controllers\ActivityController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdvisorController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProgramController;
use App\Http\Controllers\StudentController;
use Illuminate\Support\Facades\Route;

// Redirect to the login page if accessed via the root URL
Route::get('/', function () {
    return redirect()->route('login'); // Redirect to the login route
})->name('home');
// Admin Login Routes
Route::get('/admin/login', [AuthController::class, 'showAdminLoginForm'])->name('admin.login');
Route::post('/admin/login', [AuthController::class, 'adminLogin'])->name('admin.login.submit');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
// Show login form for all roles (admin, student, advisor)
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');

// Handle the login form submission for all roles

// Show the registration form for all roles
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');


Route::post('/students/import', [StudentController::class, 'import']);
// Student Login Route
Route::post('/student/login', [StudentController::class, 'login'])->name('student.login.submit');

// // Admin-specific routes

Route::get('/admin/register', [AuthController::class, 'showAdminRegisterForm'])->name('admin.register');
Route::post('/admin/register', [AuthController::class, 'adminRegister'])->name('admin.register.submit');

// Student Routes
Route::middleware('auth:student')->group(function () {
    Route::get('/student/dashboard', [StudentController::class, 'dashboard'])->name('student.dashboard');
    Route::get('students/{id}', [StudentController::class, 'showProfile'])->name('student.showProfile');
    Route::get('/meet', [StudentController::class, 'meet'])->name('meet.meet');
    // Route for editing an activity
    Route::get('/appointments/{id}/edit', [ActivityController::class, 'edit'])->name('activities.edit');

// Route for updating the activity
    Route::put('/appointments/{id}', [ActivityController::class, 'update'])->name('activities.update');

    Route::get('/appointments/{id}/delete', [ActivityController::class, 'destroy'])->name('activities.destroy');
    Route::get('/meetings/create', [ActivityController::class, 'create'])->name('meetings.create');
});
Route::post('/student/logout', [StudentController::class, 'logout'])->name('student.logout');

// Advisor Routes
Route::middleware('auth:advisor')->group(function () {
    // Advisor Dashboard
    // Route::get('/advisor/dashboard', function () {
    //     return view('advisor.dashboard');
    // })->name('advisor.dashboard');
    Route::get('/advisor/dashboard', [AdvisorController::class, 'dashboard'])->name('advisor.dashboard');
    Route::put('/appointments/advisor/{id}', [ActivityController::class, 'updateForAdvisor'])->name('appointments.updateForAdvisor');
    Route::get('/calendar', [AdvisorController::class, 'meet'])->name('calendar.meet');
    Route::get('/advisor', [StudentController::class, 'indexforadvisor'])->name('students.indexforadvisor');

});
Route::post('/advisor/logout', [AdvisorController::class, 'logout'])->name('advisor.logout');

// // Admin Routes
Route::middleware('auth:admin')->group(function () {
    // Admin Dashboard
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');

    // Admin Routes for managing advisors
    Route::get('/advisors/create', [AdvisorController::class, 'create'])->name('advisors.create');
    Route::post('/advisors', [AdvisorController::class, 'store'])->name('advisors.store');
    Route::get('/advisors', [AdvisorController::class, 'index'])->name('advisors.index');
    Route::get('/advisors/{id}/edit', [AdvisorController::class, 'edit'])->name('advisors.edit');
    Route::put('/advisors/{id}', [AdvisorController::class, 'update'])->name('advisors.update');
    Route::delete('/advisors/{id}', [AdvisorController::class, 'destroy'])->name('advisors.destroy');
    Route::get('/advisors/{id}', [AdvisorController::class, 'show'])->name('advisor.show');

    Route::get('/meetall', [AdminController::class, 'calendar'])->name('calendar.calendar');
    // Admin Routes for managing students
    Route::get('/admin/students', [StudentController::class, 'indexforAdmin'])->name('students.indexforAdmin');
    Route::get('/students   /create', [StudentController::class, 'create'])->name('students.create');
    Route::post('/students', [StudentController::class, 'store'])->name('students.store');
    Route::get('/students/{id}/edit', [StudentController::class, 'edit'])->name('students.edit');
    Route::put('/students/{id}', [StudentController::class, 'update'])->name('students.update');

    Route::delete('/students/{id}', [StudentController::class, 'destroy'])->name('students.destroy');
    Route::get('/students', [StudentController::class, 'index'])->name('students.index');

    // Route for viewing a student's details
    Route::get('/student/{id}', [StudentController::class, 'show'])->name('student.show');

    // Show program list
    Route::get('/programs', [ProgramController::class, 'index'])->name('programs.index');

    // Show create form for new program
    Route::get('/programs/create', [ProgramController::class, 'create'])->name('programs.create');

    // Store a new program
    Route::post('/programs', [ProgramController::class, 'store'])->name('programs.store');

    // Show edit form for program
    Route::get('/programs/{id}/edit', [ProgramController::class, 'edit'])->name('programs.edit');

    // Update program data
    Route::put('/programs/{id}', [ProgramController::class, 'update'])->name('programs.update');

    // Delete a program
    Route::delete('/programs/{id}', [ProgramController::class, 'destroy'])->name('programs.destroy');

    // Admin logout
});
Route::post('/admin/logout', [AuthController::class, 'logout'])->name('admin.logout');

Route::post('/meetings/store', [ActivityController::class, 'store'])->name('meetings.store');

// Route::middleware(['multi-auth'])->group(function () {
//     // Advisor and Admin Routes
// });
