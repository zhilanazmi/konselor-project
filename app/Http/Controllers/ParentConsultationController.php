<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreParentConsultationRequest;
use App\Http\Requests\UpdateParentConsultationRequest;
use App\Models\AcademicYear;
use App\Models\Guardian;
use App\Models\ParentConsultation;
use App\Models\Student;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ParentConsultationController extends Controller
{
    public function index(Request $request): View
    {
        $consultations = ParentConsultation::query()
            ->with(['guardian', 'student', 'counselor', 'academicYear'])
            ->when($request->search, function ($query, $search) {
                $query->whereHas('student', fn ($q) => $q->where('full_name', 'like', "%{$search}%"))
                    ->orWhereHas('guardian', fn ($q) => $q->where('full_name', 'like', "%{$search}%"));
            })
            ->when($request->academic_year_id, fn ($query, $id) => $query->where('academic_year_id', $id))
            ->when($request->status, fn ($query, $status) => $query->where('status', $status))
            ->when($request->requested_by, fn ($query, $requestedBy) => $query->where('requested_by', $requestedBy))
            ->latest('scheduled_at')
            ->paginate(15)
            ->withQueryString();

        $academicYears = AcademicYear::query()->orderByDesc('start_date')->get();

        return view('parent-consultations.index', [
            'consultations' => $consultations,
            'academicYears' => $academicYears,
            'pageTitle' => 'Konsultasi Orang Tua',
            'activePage' => 'Konsultasi Orang Tua',
        ]);
    }

    public function create(): View
    {
        $academicYears = AcademicYear::query()->orderByDesc('start_date')->get();
        $activeAcademicYear = AcademicYear::query()->where('is_active', true)->first();
        $guardians = Guardian::query()->orderBy('full_name')->get();
        $students = Student::query()->orderBy('full_name')->get();

        return view('parent-consultations.create', [
            'academicYears' => $academicYears,
            'activeAcademicYear' => $activeAcademicYear,
            'guardians' => $guardians,
            'students' => $students,
            'pageTitle' => 'Tambah Konsultasi Orang Tua',
            'activePage' => 'Konsultasi Orang Tua',
        ]);
    }

    public function store(StoreParentConsultationRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $validated['counselor_id'] = auth()->id();

        ParentConsultation::query()->create($validated);

        return redirect()
            ->route('guru-bk.parent-consultations.index')
            ->with('success', 'Data konsultasi orang tua berhasil ditambahkan.');
    }

    public function show(ParentConsultation $parentConsultation): View
    {
        $parentConsultation->load(['guardian', 'student', 'counselor', 'academicYear']);

        return view('parent-consultations.show', [
            'consultation' => $parentConsultation,
            'pageTitle' => 'Detail Konsultasi Orang Tua',
            'activePage' => 'Konsultasi Orang Tua',
        ]);
    }

    public function edit(ParentConsultation $parentConsultation): View
    {
        $academicYears = AcademicYear::query()->orderByDesc('start_date')->get();
        $guardians = Guardian::query()->orderBy('full_name')->get();
        $students = Student::query()->orderBy('full_name')->get();

        return view('parent-consultations.edit', [
            'consultation' => $parentConsultation,
            'academicYears' => $academicYears,
            'guardians' => $guardians,
            'students' => $students,
            'pageTitle' => 'Edit Konsultasi Orang Tua',
            'activePage' => 'Konsultasi Orang Tua',
        ]);
    }

    public function update(UpdateParentConsultationRequest $request, ParentConsultation $parentConsultation): RedirectResponse
    {
        $parentConsultation->update($request->validated());

        return redirect()
            ->route('guru-bk.parent-consultations.show', $parentConsultation)
            ->with('success', 'Data konsultasi orang tua berhasil diperbarui.');
    }

    public function destroy(ParentConsultation $parentConsultation): RedirectResponse
    {
        $parentConsultation->delete();

        return redirect()
            ->route('guru-bk.parent-consultations.index')
            ->with('success', 'Data konsultasi orang tua berhasil dihapus.');
    }
}
