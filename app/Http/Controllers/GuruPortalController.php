<?php

namespace App\Http\Controllers;

use App\Models\HomeroomConsultation;
use App\Models\SubjectTeacherConsultation;
use Illuminate\Http\Request;
use Illuminate\View\View;

class GuruPortalController extends Controller
{
    public function homeroomConsultationIndex(Request $request): View
    {
        $teacher = $request->user()->teacher;

        if (! $teacher) {
            abort(404, 'Profil Guru Anda tidak ditemukan.');
        }

        $consultations = HomeroomConsultation::query()
            ->where('teacher_id', $teacher->id)
            ->with(['counselor', 'student', 'academicYear'])
            ->latest('consultation_date')
            ->paginate(15)
            ->withQueryString();

        return view('guru.homeroom-consultations.index', [
            'consultations' => $consultations,
        ]);
    }

    public function homeroomConsultationShow(Request $request, HomeroomConsultation $consultation): View
    {
        $teacher = $request->user()->teacher;

        if (! $teacher || $consultation->teacher_id !== $teacher->id) {
            abort(403, 'Anda tidak memiliki akses untuk melihat data konsultasi ini.');
        }

        $consultation->load(['counselor', 'student', 'academicYear']);

        return view('guru.homeroom-consultations.show', [
            'consultation' => $consultation,
        ]);
    }

    public function subjectConsultationIndex(Request $request): View
    {
        $teacher = $request->user()->teacher;

        if (! $teacher) {
            abort(404, 'Profil Guru Anda tidak ditemukan.');
        }

        $consultations = SubjectTeacherConsultation::query()
            ->where('teacher_id', $teacher->id)
            ->with(['counselor', 'student', 'academicYear'])
            ->latest('consultation_date')
            ->paginate(15)
            ->withQueryString();

        return view('guru.subject-consultations.index', [
            'consultations' => $consultations,
        ]);
    }

    public function subjectConsultationShow(Request $request, SubjectTeacherConsultation $consultation): View
    {
        $teacher = $request->user()->teacher;

        if (! $teacher || $consultation->teacher_id !== $teacher->id) {
            abort(403, 'Anda tidak memiliki akses untuk melihat data konsultasi ini.');
        }

        $consultation->load(['counselor', 'student', 'academicYear']);

        return view('guru.subject-consultations.show', [
            'consultation' => $consultation,
        ]);
    }

    public function classroomIndex(Request $request): View
    {
        $teacher = $request->user()->teacher;

        if (! $teacher) {
            abort(404, 'Profil Guru Anda tidak ditemukan.');
        }

        $classrooms = $teacher->homeroomClassrooms()
            ->with(['academicYear', 'students'])
            ->latest('id')
            ->get();

        return view('guru.classrooms.index', [
            'classrooms' => $classrooms,
            'teacher' => $teacher,
        ]);
    }
}
