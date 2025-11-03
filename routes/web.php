<?php

use App\Http\Controllers\{
    ProfileController,
    DashboardController,
    GradeController,
    AttendanceController,
    ClassController,
    SubjectController,
    UserController,
    StudentController,
    TeacherController,
    ParentController,
    NotificationController,
    SettingsController
};
use Illuminate\Support\Facades\Route;

// Redirect root to login
Route::get('/', function () {
    return redirect()->route('login');
});

// Dashboard - Role-based routing
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});

// Profile routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/password', [ProfileController::class, 'updatePassword'])->name('password.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Notification routes - All authenticated users
Route::middleware(['auth'])->group(function () {
    Route::get('/notifications/data', [NotificationController::class, 'index'])->name('notifications.data');
    Route::get('/notifications', [NotificationController::class, 'all'])->name('notifications.all');
    Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/mark-all-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.markAllRead');
    Route::delete('/notifications/{id}', [NotificationController::class, 'delete'])->name('notifications.delete');
    
    // Settings routes
    Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
    Route::put('/settings', [SettingsController::class, 'update'])->name('settings.update');
});

// Director routes - Full access
Route::middleware(['auth', 'role:directeur'])->prefix('directeur')->name('directeur.')->group(function () {
    Route::resource('users', UserController::class);
    Route::resource('students', StudentController::class);
    Route::resource('teachers', TeacherController::class);
    Route::resource('parents', ParentController::class);
    Route::resource('classes', ClassController::class);
    Route::resource('subjects', SubjectController::class);
    Route::get('grades', [GradeController::class, 'indexAll'])->name('grades.index');
    Route::get('attendance', [AttendanceController::class, 'indexAll'])->name('attendance.index');
    Route::get('reports', function () {
        return view('directeur.reports');
    })->name('reports');
});

// Teacher routes - Manage classes, grades, and attendance
Route::middleware(['auth', 'role:enseignant'])->prefix('enseignant')->name('enseignant.')->group(function () {
    Route::resource('classes', ClassController::class)->only(['index', 'show']);
    Route::resource('subjects', SubjectController::class)->only(['index', 'show']);
    Route::resource('grades', GradeController::class);
    Route::resource('attendance', AttendanceController::class);
    Route::get('attendance/mark', [AttendanceController::class, 'create'])->name('attendance.mark');
    Route::get('students', [ClassController::class, 'students'])->name('students.index');
    Route::get('students/{student}', [ClassController::class, 'studentShow'])->name('students.show');
});

// Student routes - View only
Route::middleware(['auth', 'role:etudiant'])->prefix('etudiant')->name('etudiant.')->group(function () {
    Route::get('grades', [GradeController::class, 'index'])->name('grades.index');
    Route::get('grades/{grade}', [GradeController::class, 'show'])->name('grades.show');
    Route::get('attendance', [AttendanceController::class, 'index'])->name('attendance.index');
    Route::get('subjects', [SubjectController::class, 'index'])->name('subjects.index');
    Route::get('subjects/{subject}', [SubjectController::class, 'show'])->name('subjects.show');
});

// Parent routes - View child's information
Route::middleware(['auth', 'role:parent'])->prefix('parent')->name('parent.')->group(function () {
    Route::get('grades', [GradeController::class, 'index'])->name('grades.index');
    Route::get('attendance', [AttendanceController::class, 'index'])->name('attendance.index');
    Route::get('children/{child}/grades', [ParentController::class, 'childGrades'])->name('children.grades');
    Route::get('children/{child}/attendance', [ParentController::class, 'childAttendance'])->name('children.attendance');
    Route::get('teachers', [ParentController::class, 'teachers'])->name('teachers');
});

require __DIR__.'/auth.php';
