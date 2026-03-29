<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSubjectTeacherConsultationRequest;
use App\Http\Requests\UpdateSubjectTeacherConsultationRequest;
use App\Models\AcademicYear;
use App\Models\Student;
use App\Models\SubjectTeacherConsultation;
use App\Models\Teacher;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SubjectTeacherConsultationController extends Controller
{
    public function index(Request $request): View
    {
        $consultations = SubjectTeacherConsultation::query()
            ->with(['teacher', 'student', 'counselor', 'academicYear'])
            ->when($request->search, function ($query, $search) {
                $query->whereHas('student', fn ($q) => $q->where('full_name', 'like', "%{$search}%"))
                    ->orWhereHas('teacher', fn ($q) => $q->where('full_name', 'like', "%{$search}%"))
                    ->orWhere('subject_name', 'like', "%{$search}%");
            })
            ->when($request->academic_year_id, fn ($query, $id) => $query->where('academic_year_id', $id))
            ->when($request->teacher_id, fn ($query, $id) => $query->where('teacher_id', $id))
            ->latest('consultation_date')
            ->paginate(15)
            ->withQueryString();

        $academicYears = AcademicYear::query()->orderByDesc('start_date')->get();
        $teachers = Teacher::query()->orderBy('full_name')->get();

        return view('subject-teacher-consultations.index', [
            'consultations' => $consultations,
            'academicYears' => $academicYears,
            'teachers' => $teachers,
            'pageTitle' => 'Konsultasi Guru Mata Pelajaran',
            'activePage' => 'Konsultasi Guru Mapel',
        ]);
    }

    public function create(): View
    {
        $academicYears = AcademicYear::query()->orderByDesc('start_date')->get();
        $activeAcademicYear = AcademicYear::query()->where('is_active', true)->first();
        $teachers = Teacher::query()->orderBy('full_name')->get();
        $students = Student::query()->orderBy('full_name')->get();

        return view('subject-teacher-consultations.create', [
            'academicYears' => $academicYears,
            'activeAcademicYear' => $activeAcademicYear,
            'teachers' => $teachers,
            'students' => $students,
            'pageTitle' => 'Tambah Konsultasi Guru Mapel',
            'activePage' => 'Konsultasi Guru Mapel',
        ]);
    }

    public function store(StoreSubjectTeacherConsultationRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $validated['counselor_id'] = auth()->id();

        SubjectTeacherConsultation::query()->create($validated);

        return redirect()
            ->route('guru-bk.subject-teacher-consultations.index')
            ->with('success', 'Data konsultasi guru mata pelajaran berhasil ditambahkan.');
    }

    public function show(SubjectTeacherConsultation $subjectTeacherConsultation): View
    {
        $subjectTeacherConsultation->load(['teacher', 'student', 'counselor', 'academicYear']);

        return view('subject-teacher-consultations.show', [
            'consultation' => $subjectTeacherConsultation,
            'pageTitle' => 'Detail Konsultasi Guru Mapel',
            'activePage' => 'Konsultasi Guru Mapel',
        ]);
    }

    public function edit(SubjectTeacherConsultation $subjectTeacherConsultation): View
    {
        $academicYears = AcademicYear::query()->orderByDesc('start_date')->get();
        $teachers = Teacher::query()->orderBy('full_name')->get();
        $students = Student::query()->orderBy('full_name')->get();

        return view('subject-teacher-consultations.edit', [
            'consultation' => $subjectTeacherConsultation,
            'academicYears' => $academicYears,
            'teachers' => $teachers,
            'students' => $students,
            'pageTitle' => 'Edit Konsultasi Guru Mapel',
            'activePage' => 'Konsultasi Guru Mapel',
        ]);
    }

    public function update(UpdateSubjectTeacherConsultationRequest $request, SubjectTeacherConsultation $subjectTeacherConsultation): RedirectResponse
    {
        $subjectTeacherConsultation->update($request->validated());

        return redirect()
            ->route('guru-bk.subject-teacher-consultations.show', $subjectTeacherConsultation)
            ->with('success', 'Data konsultasi guru mata pelajaran berhasil diperbarui.');
    }

    public function destroy(SubjectTeacherConsultation $subjectTeacherConsultation): RedirectResponse
    {
        $subjectTeacherConsultation->delete();

        return redirect()
            ->route('guru-bk.subject-teacher-consultations.index')
            ->with('success', 'Data konsultasi guru mata pelajaran berhasil dihapus.');
    }
}
