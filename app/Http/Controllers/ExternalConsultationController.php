<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreExternalConsultationRequest;
use App\Http\Requests\UpdateExternalConsultationRequest;
use App\Models\AcademicYear;
use App\Models\CounselingDocument;
use App\Models\ExternalConsultation;
use App\Models\SchoolSetting;
use App\Models\Student;
use App\Services\CounselingDocumentService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ExternalConsultationController extends Controller
{
    public function __construct(private readonly CounselingDocumentService $documentService) {}

    public function index(Request $request): View
    {
        $consultations = ExternalConsultation::query()
            ->with(['student', 'counselor', 'academicYear'])
            ->when($request->search, function ($query, $search) {
                $query->where('external_party_name', 'like', "%{$search}%")
                    ->orWhereHas('student', fn ($q) => $q->where('full_name', 'like', "%{$search}%"));
            })
            ->when($request->academic_year_id, fn ($query, $id) => $query->where('academic_year_id', $id))
            ->latest('consultation_date')
            ->paginate(15)
            ->withQueryString();

        $academicYears = AcademicYear::query()->orderByDesc('start_date')->get();

        return view('external-consultations.index', [
            'consultations' => $consultations,
            'academicYears' => $academicYears,
            'pageTitle' => 'Konsultasi Pihak Luar',
            'activePage' => 'Konsultasi Pihak Luar',
        ]);
    }

    public function create(): View
    {
        $academicYears = AcademicYear::query()->orderByDesc('start_date')->get();
        $activeAcademicYear = AcademicYear::query()->where('is_active', true)->first();
        $students = Student::query()->orderBy('full_name')->get();

        return view('external-consultations.create', [
            'academicYears' => $academicYears,
            'activeAcademicYear' => $activeAcademicYear,
            'students' => $students,
            'pageTitle' => 'Tambah Konsultasi Pihak Luar',
            'activePage' => 'Konsultasi Pihak Luar',
        ]);
    }

    public function store(StoreExternalConsultationRequest $request): RedirectResponse
    {
        $validated = $request->safe()->except(['documents']);
        $validated['counselor_id'] = auth()->id();

        $consultation = ExternalConsultation::query()->create($validated);

        if ($request->hasFile('documents')) {
            $this->documentService->storeDocuments($consultation, $request->file('documents'));
        }

        return redirect()
            ->route('guru-bk.external-consultations.show', $consultation)
            ->with('success', 'Data konsultasi pihak luar berhasil ditambahkan.');
    }

    public function show(ExternalConsultation $externalConsultation): View
    {
        $externalConsultation->load(['student', 'counselor', 'academicYear', 'documents']);

        $principalName = SchoolSetting::getPrincipalName();
        $principalNip = SchoolSetting::getPrincipalNip();

        return view('external-consultations.show', [
            'consultation' => $externalConsultation,
            'principalName' => $principalName,
            'principalNip' => $principalNip,
            'pageTitle' => 'Detail Konsultasi Pihak Luar',
            'activePage' => 'Konsultasi Pihak Luar',
        ]);
    }

    public function edit(ExternalConsultation $externalConsultation): View
    {
        $externalConsultation->load('documents');
        $academicYears = AcademicYear::query()->orderByDesc('start_date')->get();
        $students = Student::query()->orderBy('full_name')->get();

        return view('external-consultations.edit', [
            'consultation' => $externalConsultation,
            'academicYears' => $academicYears,
            'students' => $students,
            'pageTitle' => 'Edit Konsultasi Pihak Luar',
            'activePage' => 'Konsultasi Pihak Luar',
        ]);
    }

    public function update(UpdateExternalConsultationRequest $request, ExternalConsultation $externalConsultation): RedirectResponse
    {
        $externalConsultation->update($request->safe()->except(['documents']));

        if ($request->hasFile('documents')) {
            $this->documentService->storeDocuments($externalConsultation, $request->file('documents'));
        }

        return redirect()
            ->route('guru-bk.external-consultations.show', $externalConsultation)
            ->with('success', 'Data konsultasi pihak luar berhasil diperbarui.');
    }

    public function destroy(ExternalConsultation $externalConsultation): RedirectResponse
    {
        $this->documentService->deleteAllDocuments($externalConsultation);
        $externalConsultation->delete();

        return redirect()
            ->route('guru-bk.external-consultations.index')
            ->with('success', 'Data konsultasi pihak luar berhasil dihapus.');
    }

    public function destroyDocument(ExternalConsultation $externalConsultation, CounselingDocument $document): RedirectResponse
    {
        $this->documentService->deleteDocument($document);

        return back()->with('success', 'Dokumen berhasil dihapus.');
    }

    public function printPdf(ExternalConsultation $externalConsultation): View
    {
        $externalConsultation->load(['student', 'counselor', 'academicYear', 'documents']);

        $principalName = SchoolSetting::getPrincipalName();
        $principalNip = SchoolSetting::getPrincipalNip();

        return view('external-consultations.pdf', [
            'consultation' => $externalConsultation,
            'principalName' => $principalName,
            'principalNip' => $principalNip,
        ]);
    }
}
