<?php

namespace App\Http\Controllers;

use App\Models\GroupCounseling;
use App\Models\IndividualCounseling;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SiswaCounselingController extends Controller
{
    public function index(Request $request): View
    {
        $student = $request->user()->student;

        if (! $student) {
            abort(404, 'Profil Siswa Anda tidak ditemukan.');
        }

        $counselings = IndividualCounseling::query()
            ->where('student_id', $student->id)
            ->with(['counselor', 'academicYear'])
            ->latest('scheduled_at')
            ->paginate(15)
            ->withQueryString();

        return view('siswa.counselings.index', [
            'counselings' => $counselings,
            'student' => $student,
        ]);
    }

    public function show(Request $request, IndividualCounseling $counseling): View
    {
        $student = $request->user()->student;

        if (! $student || $counseling->student_id !== $student->id) {
            abort(403, 'Anda tidak memiliki akses untuk melihat data konseling ini.');
        }

        $counseling->load(['counselor', 'academicYear', 'student']);

        return view('siswa.counselings.show', [
            'counseling' => $counseling,
        ]);
    }

    public function groupIndex(Request $request): View
    {
        $student = $request->user()->student;

        if (! $student) {
            abort(404, 'Profil Siswa Anda tidak ditemukan.');
        }

        $groupCounselings = GroupCounseling::query()
            ->whereHas('participants', fn ($q) => $q->where('students.id', $student->id))
            ->with(['counselor', 'academicYear'])
            ->latest('scheduled_at')
            ->paginate(15)
            ->withQueryString();

        return view('siswa.group-counselings.index', [
            'groupCounselings' => $groupCounselings,
            'student' => $student,
        ]);
    }

    public function groupShow(Request $request, GroupCounseling $groupCounseling): View
    {
        $student = $request->user()->student;

        if (! $student) {
            abort(404, 'Profil Siswa Anda tidak ditemukan.');
        }

        $isParticipant = $groupCounseling->participants()->where('students.id', $student->id)->exists();

        if (! $isParticipant) {
            abort(403, 'Anda bukan partisipan dalam sesi konseling kelompok ini.');
        }

        $groupCounseling->load(['counselor', 'academicYear', 'participants']);

        $myNotes = $groupCounseling->participants
            ->where('id', $student->id)
            ->first()
            ?->pivot
            ?->notes;

        return view('siswa.group-counselings.show', [
            'groupCounseling' => $groupCounseling,
            'myNotes' => $myNotes,
        ]);
    }
}
