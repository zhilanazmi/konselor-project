<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreHomeroomConsultationRequest;
use App\Http\Requests\UpdateHomeroomConsultationRequest;
use App\Models\AcademicYear;
use App\Models\CounselingDocument;
use App\Models\HomeroomConsultation;
use App\Models\SchoolSetting;
use App\Models\Student;
use App\Models\Teacher;
use App\Services\CounselingDocumentService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HomeroomConsultationController extends Controller
{
    public function __construct(private readonly CounselingDocumentService $documentService) {}

    public function index(Request $request): View
    {
        $consultations = HomeroomConsultation::query()
            ->with(['teacher', 'student', 'counselor', 'academicYear'])
            ->when($request->search, function ($query, $search) {
                $query->whereHas('student', fn ($q) => $q->where('full_name', 'like', "%{$search}%"))
                    ->orWhereHas('teacher', fn ($q) => $q->where('full_name', 'like', "%{$search}%"));
            })
            ->when($request->academic_year_id, fn ($query, $id) => $query->where('academic_year_id', $id))
            ->when($request->teacher_id, fn ($query, $id) => $query->where('teacher_id', $id))
            ->latest('consultation_date')
            ->paginate(15)
            ->withQueryString();

        $academicYears = AcademicYear::query()->orderByDesc('start_date')->get();
        $teachers = Teacher::query()->orderBy('full_name')->get();

        return view('homeroom-consultations.index', [
            'consultations' => $consultations,
            'academicYears' => $academicYears,
            'teachers' => $teachers,
            'pageTitle' => 'Konsultasi Wali Kelas',
            'activePage' => 'Konsultasi Wali Kelas',
        ]);
    }

    public function create(): View
    {
        $academicYears = AcademicYear::query()->orderByDesc('start_date')->get();
        $activeAcademicYear = AcademicYear::query()->where('is_active', true)->first();
        $teachers = Teacher::query()->with('homeroomClassrooms')->orderBy('full_name')->get();
        $students = Student::query()->orderBy('full_name')->get();

        return view('homeroom-consultations.create', [
            'academicYears' => $academicYears,
            'activeAcademicYear' => $activeAcademicYear,
            'teachers' => $teachers,
            'students' => $students,
            'pageTitle' => 'Tambah Konsultasi Wali Kelas',
            'activePage' => 'Konsultasi Wali Kelas',
        ]);
    }

    public function store(StoreHomeroomConsultationRequest $request): RedirectResponse
    {
        $validated = $request->safe()->except(['documents']);
        $validated['counselor_id'] = auth()->id();

        $consultation = HomeroomConsultation::query()->create($validated);

        if ($request->hasFile('documents')) {
            $this->documentService->storeDocuments($consultation, $request->file('documents'));
        }

        return redirect()
            ->route('guru-bk.homeroom-consultations.show', $consultation)
            ->with('success', 'Data konsultasi wali kelas berhasil ditambahkan.');
    }

    public function show(HomeroomConsultation $homeroomConsultation): View
    {
        $homeroomConsultation->load(['teacher', 'student', 'counselor', 'academicYear', 'documents']);

        $principalName = SchoolSetting::getPrincipalName();
        $principalNip = SchoolSetting::getPrincipalNip();

        return view('homeroom-consultations.show', [
            'consultation' => $homeroomConsultation,
            'principalName' => $principalName,
            'principalNip' => $principalNip,
            'pageTitle' => 'Detail Konsultasi Wali Kelas',
            'activePage' => 'Konsultasi Wali Kelas',
        ]);
    }

    public function edit(HomeroomConsultation $homeroomConsultation): View
    {
        $homeroomConsultation->load('documents');
        $academicYears = AcademicYear::query()->orderByDesc('start_date')->get();
        $teachers = Teacher::query()->with('homeroomClassrooms')->orderBy('full_name')->get();
        $students = Student::query()->orderBy('full_name')->get();

        return view('homeroom-consultations.edit', [
            'consultation' => $homeroomConsultation,
            'academicYears' => $academicYears,
            'teachers' => $teachers,
            'students' => $students,
            'pageTitle' => 'Edit Konsultasi Wali Kelas',
            'activePage' => 'Konsultasi Wali Kelas',
        ]);
    }

    public function update(UpdateHomeroomConsultationRequest $request, HomeroomConsultation $homeroomConsultation): RedirectResponse
    {
        $homeroomConsultation->update($request->safe()->except(['documents']));

        if ($request->hasFile('documents')) {
            $this->documentService->storeDocuments($homeroomConsultation, $request->file('documents'));
        }

        return redirect()
            ->route('guru-bk.homeroom-consultations.show', $homeroomConsultation)
            ->with('success', 'Data konsultasi wali kelas berhasil diperbarui.');
    }

    public function destroy(HomeroomConsultation $homeroomConsultation): RedirectResponse
    {
        $this->documentService->deleteAllDocuments($homeroomConsultation);
        $homeroomConsultation->delete();

        return redirect()
            ->route('guru-bk.homeroom-consultations.index')
            ->with('success', 'Data konsultasi wali kelas berhasil dihapus.');
    }

    public function destroyDocument(HomeroomConsultation $homeroomConsultation, CounselingDocument $document): RedirectResponse
    {
        $this->documentService->deleteDocument($document);

        return back()->with('success', 'Dokumen berhasil dihapus.');
    }

    public function printPdf(HomeroomConsultation $homeroomConsultation): View
    {
        $homeroomConsultation->load(['teacher', 'student', 'counselor', 'academicYear', 'documents']);

        $principalName = SchoolSetting::getPrincipalName();
        $principalNip = SchoolSetting::getPrincipalNip();

        return view('homeroom-consultations.pdf', [
            'consultation' => $homeroomConsultation,
            'principalName' => $principalName,
            'principalNip' => $principalNip,
        ]);
    }
}
