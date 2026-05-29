<?php

use App\Http\Controllers\AcademicYearController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\ClassroomController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ExternalConsultationController;
use App\Http\Controllers\GroupCounselingController;
use App\Http\Controllers\GuardianController;
use App\Http\Controllers\HomeroomConsultationController;
use App\Http\Controllers\IndividualCounselingController;
use App\Http\Controllers\ParentConsultationController;
use App\Http\Controllers\PopularTopicController;
use App\Http\Controllers\SchoolSettingController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\SubjectTeacherConsultationController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WelcomeController;
use Illuminate\Support\Facades\Route;

Route::get('/', [WelcomeController::class, 'index'])->name('home');

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
        // School Settings
        Route::get('school-settings', [SchoolSettingController::class, 'edit'])->name('school-settings.edit');
        Route::put('school-settings', [SchoolSettingController::class, 'update'])->name('school-settings.update');
    });

    // Guru BK Routes
    Route::middleware('role:guru_bk')->prefix('guru-bk')->name('guru-bk.')->group(function () {
        // Individual Counseling
        Route::resource('individual-counselings', IndividualCounselingController::class);
        Route::get('individual-counselings/{individualCounseling}/pdf', [IndividualCounselingController::class, 'printPdf'])->name('individual-counselings.pdf');
        Route::delete('individual-counselings/{individualCounseling}/documents/{document}', [IndividualCounselingController::class, 'destroyDocument'])->name('individual-counselings.documents.destroy');

        // Group Counseling
        Route::resource('group-counselings', GroupCounselingController::class);
        Route::get('group-counselings/{groupCounseling}/pdf', [GroupCounselingController::class, 'printPdf'])->name('group-counselings.pdf');
        Route::delete('group-counselings/{groupCounseling}/documents/{document}', [GroupCounselingController::class, 'destroyDocument'])->name('group-counselings.documents.destroy');
        Route::post('group-counselings/{groupCounseling}/participants', [GroupCounselingController::class, 'addParticipant'])->name('group-counselings.participants.store');
        Route::delete('group-counselings/{groupCounseling}/participants/{student}', [GroupCounselingController::class, 'removeParticipant'])->name('group-counselings.participants.destroy');
        Route::patch('group-counselings/{groupCounseling}/participants/{student}/notes', [GroupCounselingController::class, 'updateParticipantNotes'])->name('group-counselings.participants.update-notes');

        // Homeroom Consultations
        Route::resource('homeroom-consultations', HomeroomConsultationController::class);
        Route::get('homeroom-consultations/{homeroomConsultation}/pdf', [HomeroomConsultationController::class, 'printPdf'])->name('homeroom-consultations.pdf');
        Route::delete('homeroom-consultations/{homeroomConsultation}/documents/{document}', [HomeroomConsultationController::class, 'destroyDocument'])->name('homeroom-consultations.documents.destroy');

        // Subject Teacher Consultations
        Route::resource('subject-teacher-consultations', SubjectTeacherConsultationController::class);

        // Parent Consultations
        Route::resource('parent-consultations', ParentConsultationController::class);
        Route::get('parent-consultations/{parentConsultation}/pdf', [ParentConsultationController::class, 'printPdf'])->name('parent-consultations.pdf');
        Route::delete('parent-consultations/{parentConsultation}/documents/{document}', [ParentConsultationController::class, 'destroyDocument'])->name('parent-consultations.documents.destroy');

        // External Consultations
        Route::resource('external-consultations', ExternalConsultationController::class);
        Route::get('external-consultations/{externalConsultation}/pdf', [ExternalConsultationController::class, 'printPdf'])->name('external-consultations.pdf');
        Route::delete('external-consultations/{externalConsultation}/documents/{document}', [ExternalConsultationController::class, 'destroyDocument'])->name('external-consultations.documents.destroy');

        // Popular Topics
        Route::resource('popular-topics', PopularTopicController::class)->except(['show']);
        Route::patch('popular-topics/{popularTopic}/toggle-status', [PopularTopicController::class, 'toggleStatus'])->name('popular-topics.toggle-status');
        
        // Test route for debugging
        Route::get('test-auth', function () {
            return response()->json([
                'user' => auth()->user()->only(['id', 'name', 'email']),
                'role' => auth()->user()->role->value,
                'is_guru_bk' => auth()->user()->isGuruBk(),
                'message' => 'Authentication successful!'
            ]);
        })->name('test-auth');
    });
});
