<?php

namespace App\Http\Controllers;

use App\Enums\UserRole;
use App\Models\GroupCounseling;
use App\Models\HomeroomConsultation;
use App\Models\IndividualCounseling;
use App\Models\ParentConsultation;
use App\Models\SubjectTeacherConsultation;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();
        $extra = [];

        match ($user->role) {
            UserRole::Siswa => $extra = $this->loadSiswaData($user),
            UserRole::OrangTua => $extra = $this->loadOrangTuaData($user),
            UserRole::Guru => $extra = $this->loadGuruData($user),
            default => null,
        };

        return view('dashboard', array_merge(['user' => $user], $extra));
    }

    /**
     * @return array<string, mixed>
     */
    private function loadSiswaData($user): array
    {
        $student = $user->student;

        if (! $student) {
            return [];
        }

        $recentCounselings = IndividualCounseling::query()
            ->where('student_id', $student->id)
            ->with('counselor')
            ->latest('scheduled_at')
            ->limit(5)
            ->get();

        $groupCounselingCount = GroupCounseling::query()
            ->whereHas('participants', fn ($q) => $q->where('students.id', $student->id))
            ->count();

        return [
            'recentCounselings' => $recentCounselings,
            'groupCounselingCount' => $groupCounselingCount,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function loadOrangTuaData($user): array
    {
        $guardian = $user->guardian;

        if (! $guardian) {
            return [];
        }

        $children = $guardian->students()
            ->with(['classrooms.academicYear'])
            ->withCount('individualCounselings')
            ->get();

        $recentConsultations = ParentConsultation::query()
            ->where('guardian_id', $guardian->id)
            ->with(['counselor', 'student'])
            ->latest('scheduled_at')
            ->limit(5)
            ->get();

        return [
            'children' => $children,
            'recentConsultations' => $recentConsultations,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function loadGuruData($user): array
    {
        $teacher = $user->teacher;

        if (! $teacher) {
            return [];
        }

        $classrooms = $teacher->homeroomClassrooms()
            ->with('academicYear')
            ->withCount('students')
            ->get();

        $recentConsultations = HomeroomConsultation::query()
            ->where('teacher_id', $teacher->id)
            ->with(['counselor', 'student'])
            ->latest('consultation_date')
            ->limit(5)
            ->get();

        $subjectConsultationCount = SubjectTeacherConsultation::query()
            ->where('teacher_id', $teacher->id)
            ->count();

        return [
            'classrooms' => $classrooms,
            'recentConsultations' => $recentConsultations,
            'subjectConsultationCount' => $subjectConsultationCount,
        ];
    }
}
