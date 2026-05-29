<?php

namespace App\Http\Controllers;

use App\Models\AcademicYear;
use App\Models\ExternalConsultation;
use App\Models\GroupCounseling;
use App\Models\HomeroomConsultation;
use App\Models\IndividualCounseling;
use App\Models\ParentConsultation;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();
        $activeAcademicYear = AcademicYear::query()->where('is_active', true)->first();

        // Statistik untuk Guru BK
        $stats = [];
        if ($user->role === 'guru_bk') {
            $stats = [
                'total_students' => Student::count(),
                'individual_counselings' => IndividualCounseling::query()
                    ->where('counselor_id', $user->id)
                    ->when($activeAcademicYear, fn ($q) => $q->where('academic_year_id', $activeAcademicYear->id))
                    ->count(),
                'group_counselings' => GroupCounseling::query()
                    ->where('counselor_id', $user->id)
                    ->when($activeAcademicYear, fn ($q) => $q->where('academic_year_id', $activeAcademicYear->id))
                    ->count(),
                'homeroom_consultations' => HomeroomConsultation::query()
                    ->where('counselor_id', $user->id)
                    ->when($activeAcademicYear, fn ($q) => $q->where('academic_year_id', $activeAcademicYear->id))
                    ->count(),
                'parent_consultations' => ParentConsultation::query()
                    ->where('counselor_id', $user->id)
                    ->when($activeAcademicYear, fn ($q) => $q->where('academic_year_id', $activeAcademicYear->id))
                    ->count(),
                'external_consultations' => ExternalConsultation::query()
                    ->where('counselor_id', $user->id)
                    ->when($activeAcademicYear, fn ($q) => $q->where('academic_year_id', $activeAcademicYear->id))
                    ->count(),
            ];

            // Statistik per status
            $stats['scheduled_count'] = IndividualCounseling::query()
                ->where('counselor_id', $user->id)
                ->where('status', 'scheduled')
                ->when($activeAcademicYear, fn ($q) => $q->where('academic_year_id', $activeAcademicYear->id))
                ->count();

            $stats['ongoing_count'] = IndividualCounseling::query()
                ->where('counselor_id', $user->id)
                ->where('status', 'ongoing')
                ->when($activeAcademicYear, fn ($q) => $q->where('academic_year_id', $activeAcademicYear->id))
                ->count();

            $stats['completed_count'] = IndividualCounseling::query()
                ->where('counselor_id', $user->id)
                ->where('status', 'completed')
                ->when($activeAcademicYear, fn ($q) => $q->where('academic_year_id', $activeAcademicYear->id))
                ->count();

            // Aktivitas terbaru
            $recentActivities = collect();

            $recentIndividual = IndividualCounseling::query()
                ->with(['student', 'academicYear'])
                ->where('counselor_id', $user->id)
                ->latest()
                ->limit(5)
                ->get()
                ->map(fn ($item) => [
                    'type' => 'individual',
                    'title' => 'Bimbingan Individu',
                    'description' => $item->student->full_name.' - '.$item->category,
                    'date' => $item->scheduled_at,
                    'status' => $item->status,
                    'url' => route('guru-bk.individual-counselings.show', $item),
                ]);

            $recentGroup = GroupCounseling::query()
                ->with(['academicYear'])
                ->withCount('participants')
                ->where('counselor_id', $user->id)
                ->latest()
                ->limit(5)
                ->get()
                ->map(fn ($item) => [
                    'type' => 'group',
                    'title' => 'Bimbingan Kelompok',
                    'description' => $item->topic.' ('.$item->participants_count.' peserta)',
                    'date' => $item->scheduled_at,
                    'status' => $item->status,
                    'url' => route('guru-bk.group-counselings.show', $item),
                ]);

            $recentActivities = $recentIndividual->concat($recentGroup)
                ->sortByDesc('date')
                ->take(10);

            $stats['recent_activities'] = $recentActivities;
        }

        return view('dashboard', compact('user', 'stats', 'activeAcademicYear'));
    }
}
