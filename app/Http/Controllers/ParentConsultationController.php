<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreParentConsultationRequest;
use App\Http\Requests\UpdateParentConsultationRequest;
use App\Models\AcademicYear;
use App\Models\CounselingDocument;
use App\Models\Guardian;
use App\Models\ParentConsultation;
use App\Models\SchoolSetting;
use App\Models\Student;
use App\Services\CounselingDocumentService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ParentConsultationController extends Controller
{
    public function __construct(private readonly CounselingDocumentService $documentService) {}

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
        $validated = $request->safe()->except(['documents']);
        $validated['counselor_id'] = auth()->id();

        $consultation = ParentConsultation::query()->create($validated);

        if ($request->hasFile('documents')) {
            $this->documentService->storeDocuments($consultation, $request->file('documents'));
        }

        return redirect()
            ->route('guru-bk.parent-consultations.show', $consultation)
            ->with('success', 'Data konsultasi orang tua berhasil ditambahkan.');
    }

    public function show(ParentConsultation $parentConsultation): View
    {
        $parentConsultation->load(['guardian', 'student', 'counselor', 'academicYear', 'documents']);

        $principalName = SchoolSetting::getPrincipalName();
        $principalNip = SchoolSetting::getPrincipalNip();

        return view('parent-consultations.show', [
            'consultation' => $parentConsultation,
            'principalName' => $principalName,
            'principalNip' => $principalNip,
            'pageTitle' => 'Detail Konsultasi Orang Tua',
            'activePage' => 'Konsultasi Orang Tua',
        ]);
    }

    public function edit(ParentConsultation $parentConsultation): View
    {
        $parentConsultation->load('documents');
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
        $parentConsultation->update($request->safe()->except(['documents']));

        if ($request->hasFile('documents')) {
            $this->documentService->storeDocuments($parentConsultation, $request->file('documents'));
        }

        return redirect()
            ->route('guru-bk.parent-consultations.show', $parentConsultation)
            ->with('success', 'Data konsultasi orang tua berhasil diperbarui.');
    }

    public function destroy(ParentConsultation $parentConsultation): RedirectResponse
    {
        $this->documentService->deleteAllDocuments($parentConsultation);
        $parentConsultation->delete();

        return redirect()
            ->route('guru-bk.parent-consultations.index')
            ->with('success', 'Data konsultasi orang tua berhasil dihapus.');
    }

    public function destroyDocument(ParentConsultation $parentConsultation, CounselingDocument $document): RedirectResponse
    {
        $this->documentService->deleteDocument($document);

        return back()->with('success', 'Dokumen berhasil dihapus.');
    }

    public function printPdf(ParentConsultation $parentConsultation): View
    {
        $parentConsultation->load(['guardian', 'student', 'counselor', 'academicYear', 'documents']);

        $principalName = SchoolSetting::getPrincipalName();
        $principalNip = SchoolSetting::getPrincipalNip();

        $homeroomTeacher = null;
        $classroom = $parentConsultation->student->classrooms()
            ->where('academic_year_id', $parentConsultation->academic_year_id)
            ->with('homeroomTeacher')
            ->first();

        if ($classroom) {
            $homeroomTeacher = $classroom->homeroomTeacher;
        }

        return view('parent-consultations.pdf', [
            'consultation' => $parentConsultation,
            'principalName' => $principalName,
            'principalNip' => $principalNip,
            'homeroomTeacher' => $homeroomTeacher,
        ]);
    }
}
