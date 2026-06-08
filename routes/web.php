<?php

use App\Http\Controllers\AcademicYearController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\ClassroomController;
use App\Http\Controllers\CounselingRequestController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GroupCounselingController;
use App\Http\Controllers\GuardianController;
use App\Http\Controllers\GuruPortalController;
use App\Http\Controllers\HomeroomConsultationController;
use App\Http\Controllers\IndividualCounselingController;
use App\Http\Controllers\OrangTuaPortalController;
use App\Http\Controllers\ParentConsultationController;
use App\Http\Controllers\SiswaCounselingController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\SubjectTeacherConsultationController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

// Auth Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
});

Route::post('/logout', [LoginController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

// Protected Routes
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Admin Routes
    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
        Route::resource('academic-years', AcademicYearController::class)->except(['show']);
        Route::resource('students', StudentController::class)->except(['show']);
        Route::resource('classrooms', ClassroomController::class);
        Route::resource('teachers', TeacherController::class)->except(['show']);
        Route::resource('guardians', GuardianController::class)->except(['show']);
        Route::resource('users', UserController::class)->except(['show']);
        Route::post('classrooms/{classroom}/students', [ClassroomController::class, 'addStudents'])->name('classrooms.add-students');
        Route::delete('classrooms/{classroom}/students/{student}', [ClassroomController::class, 'removeStudent'])->name('classrooms.remove-student');
    });

    // Guru BK Routes
    Route::middleware('role:guru_bk')->prefix('guru-bk')->name('guru-bk.')->group(function () {
        Route::resource('individual-counselings', IndividualCounselingController::class);
        Route::resource('group-counselings', GroupCounselingController::class);
        Route::post('group-counselings/{groupCounseling}/participants', [GroupCounselingController::class, 'addParticipant'])->name('group-counselings.participants.store');
        Route::delete('group-counselings/{groupCounseling}/participants/{student}', [GroupCounselingController::class, 'removeParticipant'])->name('group-counselings.participants.destroy');
        Route::patch('group-counselings/{groupCounseling}/participants/{student}/notes', [GroupCounselingController::class, 'updateParticipantNotes'])->name('group-counselings.participants.update-notes');
        Route::resource('homeroom-consultations', HomeroomConsultationController::class);
        Route::resource('subject-teacher-consultations', SubjectTeacherConsultationController::class);
        Route::resource('parent-consultations', ParentConsultationController::class);

        // Counseling Requests for Guru BK
        Route::resource('counseling-requests', CounselingRequestController::class)->only(['index', 'show']);
        Route::post('counseling-requests/{counselingRequest}/approve', [CounselingRequestController::class, 'approve'])->name('counseling-requests.approve');
        Route::post('counseling-requests/{counselingRequest}/reject', [CounselingRequestController::class, 'reject'])->name('counseling-requests.reject');
    });

    // Siswa Routes
    Route::middleware('role:siswa')->prefix('siswa')->name('siswa.')->group(function () {
        Route::resource('counseling-requests', CounselingRequestController::class)->only(['index', 'create', 'store', 'show']);
        Route::get('counselings', [SiswaCounselingController::class, 'index'])->name('counselings.index');
        Route::get('counselings/{counseling}', [SiswaCounselingController::class, 'show'])->name('counselings.show');
        Route::get('group-counselings', [SiswaCounselingController::class, 'groupIndex'])->name('group-counselings.index');
        Route::get('group-counselings/{groupCounseling}', [SiswaCounselingController::class, 'groupShow'])->name('group-counselings.show');
    });

    // Orang Tua Routes
    Route::middleware('role:orang_tua')->prefix('orang-tua')->name('orang-tua.')->group(function () {
        Route::resource('counseling-requests', CounselingRequestController::class)->only(['index', 'create', 'store', 'show']);
        Route::get('children', [OrangTuaPortalController::class, 'childrenIndex'])->name('children.index');
        Route::get('children/{student}/counselings', [OrangTuaPortalController::class, 'childCounselings'])->name('children.counselings');
        Route::get('children/{student}/counselings/{counseling}', [OrangTuaPortalController::class, 'childCounselingShow'])->name('children.counseling-show');
        Route::get('consultations', [OrangTuaPortalController::class, 'consultationIndex'])->name('consultations.index');
        Route::get('consultations/{consultation}', [OrangTuaPortalController::class, 'consultationShow'])->name('consultations.show');
    });

    // Guru (Non-BK) Routes
    Route::middleware('role:guru')->prefix('guru')->name('guru.')->group(function () {
        Route::get('homeroom-consultations', [GuruPortalController::class, 'homeroomConsultationIndex'])->name('homeroom-consultations.index');
        Route::get('homeroom-consultations/{consultation}', [GuruPortalController::class, 'homeroomConsultationShow'])->name('homeroom-consultations.show');
        Route::get('subject-consultations', [GuruPortalController::class, 'subjectConsultationIndex'])->name('subject-consultations.index');
        Route::get('subject-consultations/{consultation}', [GuruPortalController::class, 'subjectConsultationShow'])->name('subject-consultations.show');
        Route::get('classrooms', [GuruPortalController::class, 'classroomIndex'])->name('classrooms.index');
    });
});
