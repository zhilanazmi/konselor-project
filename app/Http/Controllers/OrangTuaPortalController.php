<?php

namespace App\Http\Controllers;

use App\Models\IndividualCounseling;
use App\Models\ParentConsultation;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OrangTuaPortalController extends Controller
{
    public function childrenIndex(Request $request): View
    {
        $guardian = $request->user()->guardian;

        if (! $guardian) {
            abort(404, 'Profil Orang Tua Anda tidak ditemukan.');
        }

        $children = $guardian->students()
            ->with(['classrooms.academicYear', 'user'])
            ->withCount('individualCounselings')
            ->get();

        return view('orang-tua.children.index', [
            'children' => $children,
            'guardian' => $guardian,
        ]);
    }

    public function childCounselings(Request $request, Student $student): View
    {
        $guardian = $request->user()->guardian;

        if (! $guardian) {
            abort(404, 'Profil Orang Tua Anda tidak ditemukan.');
        }

        $isMyChild = $guardian->students()->where('students.id', $student->id)->exists();

        if (! $isMyChild) {
            abort(403, 'Anda tidak memiliki akses untuk melihat data anak ini.');
        }

        $counselings = IndividualCounseling::query()
            ->where('student_id', $student->id)
            ->with(['counselor', 'academicYear'])
            ->latest('scheduled_at')
            ->paginate(15)
            ->withQueryString();

        return view('orang-tua.children.counselings', [
            'student' => $student,
            'counselings' => $counselings,
        ]);
    }

    public function childCounselingShow(Request $request, Student $student, IndividualCounseling $counseling): View
    {
        $guardian = $request->user()->guardian;

        if (! $guardian) {
            abort(404, 'Profil Orang Tua Anda tidak ditemukan.');
        }

        $isMyChild = $guardian->students()->where('students.id', $student->id)->exists();

        if (! $isMyChild || $counseling->student_id !== $student->id) {
            abort(403, 'Anda tidak memiliki akses untuk melihat data konseling ini.');
        }

        $counseling->load(['counselor', 'academicYear', 'student']);

        return view('orang-tua.children.counseling-show', [
            'student' => $student,
            'counseling' => $counseling,
        ]);
    }

    public function consultationIndex(Request $request): View
    {
        $guardian = $request->user()->guardian;

        if (! $guardian) {
            abort(404, 'Profil Orang Tua Anda tidak ditemukan.');
        }

        $consultations = ParentConsultation::query()
            ->where('guardian_id', $guardian->id)
            ->with(['counselor', 'student', 'academicYear'])
            ->latest('scheduled_at')
            ->paginate(15)
            ->withQueryString();

        return view('orang-tua.consultations.index', [
            'consultations' => $consultations,
        ]);
    }

    public function consultationShow(Request $request, ParentConsultation $consultation): View
    {
        $guardian = $request->user()->guardian;

        if (! $guardian || $consultation->guardian_id !== $guardian->id) {
            abort(403, 'Anda tidak memiliki akses untuk melihat data konsultasi ini.');
        }

        $consultation->load(['counselor', 'student', 'academicYear']);

        return view('orang-tua.consultations.show', [
            'consultation' => $consultation,
        ]);
    }
}
