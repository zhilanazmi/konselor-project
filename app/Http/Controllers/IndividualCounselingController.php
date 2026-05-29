<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreIndividualCounselingRequest;
use App\Http\Requests\UpdateIndividualCounselingRequest;
use App\Models\AcademicYear;
use App\Models\CounselingDocument;
use App\Models\IndividualCounseling;
use App\Models\SchoolSetting;
use App\Models\Student;
use App\Services\CounselingDocumentService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class IndividualCounselingController extends Controller
{
    public function __construct(private readonly CounselingDocumentService $documentService) {}

    public function index(Request $request): View
    {
        $counselings = IndividualCounseling::query()
            ->with(['student', 'counselor', 'academicYear'])
            ->when($request->search, function ($query, $search) {
                $query->whereHas('student', fn ($q) => $q->where('full_name', 'like', "%{$search}%"));
            })
            ->when($request->status, fn ($query, $status) => $query->where('status', $status))
            ->when($request->category, fn ($query, $category) => $query->where('category', $category))
            ->when($request->academic_year_id, fn ($query, $id) => $query->where('academic_year_id', $id))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $academicYears = AcademicYear::query()->orderByDesc('start_date')->get();

        return view('individual-counselings.index', [
            'counselings' => $counselings,
            'academicYears' => $academicYears,
            'pageTitle' => 'Konseling Individual',
            'activePage' => 'Konseling Individual',
        ]);
    }

    public function create(): View
    {
        $students = Student::query()->orderBy('full_name')->get();
        $academicYears = AcademicYear::query()->orderByDesc('start_date')->get();
        $activeAcademicYear = AcademicYear::query()->where('is_active', true)->first();

        return view('individual-counselings.create', [
            'students' => $students,
            'academicYears' => $academicYears,
            'activeAcademicYear' => $activeAcademicYear,
            'pageTitle' => 'Tambah Konseling Individual',
            'activePage' => 'Konseling Individual',
        ]);
    }

    public function store(StoreIndividualCounselingRequest $request): RedirectResponse
    {
        $validated = $request->safe()->except(['documents']);
        $validated['counselor_id'] = auth()->id();

        $counseling = IndividualCounseling::query()->create($validated);

        if ($request->hasFile('documents')) {
            $this->documentService->storeDocuments($counseling, $request->file('documents'));
        }

        return redirect()
            ->route('guru-bk.individual-counselings.show', $counseling)
            ->with('success', 'Data konseling individual berhasil ditambahkan.');
    }

    public function show(IndividualCounseling $individualCounseling): View
    {
        $individualCounseling->load(['student', 'counselor', 'academicYear', 'documents']);

        $principalName = SchoolSetting::getPrincipalName();
        $principalNip = SchoolSetting::getPrincipalNip();

        return view('individual-counselings.show', [
            'counseling' => $individualCounseling,
            'principalName' => $principalName,
            'principalNip' => $principalNip,
            'pageTitle' => 'Detail Konseling Individual',
            'activePage' => 'Konseling Individual',
        ]);
    }

    public function edit(IndividualCounseling $individualCounseling): View
    {
        $individualCounseling->load('documents');
        $students = Student::query()->orderBy('full_name')->get();
        $academicYears = AcademicYear::query()->orderByDesc('start_date')->get();

        return view('individual-counselings.edit', [
            'counseling' => $individualCounseling,
            'students' => $students,
            'academicYears' => $academicYears,
            'pageTitle' => 'Edit Konseling Individual',
            'activePage' => 'Konseling Individual',
        ]);
    }

    public function update(UpdateIndividualCounselingRequest $request, IndividualCounseling $individualCounseling): RedirectResponse
    {
        $individualCounseling->update($request->safe()->except(['documents']));

        if ($request->hasFile('documents')) {
            $this->documentService->storeDocuments($individualCounseling, $request->file('documents'));
        }

        return redirect()
            ->route('guru-bk.individual-counselings.show', $individualCounseling)
            ->with('success', 'Data konseling individual berhasil diperbarui.');
    }

    public function destroy(IndividualCounseling $individualCounseling): RedirectResponse
    {
        $this->documentService->deleteAllDocuments($individualCounseling);
        $individualCounseling->delete();

        return redirect()
            ->route('guru-bk.individual-counselings.index')
            ->with('success', 'Data konseling individual berhasil dihapus.');
    }

    public function destroyDocument(IndividualCounseling $individualCounseling, CounselingDocument $document): RedirectResponse
    {
        $this->documentService->deleteDocument($document);

        return back()->with('success', 'Dokumen berhasil dihapus.');
    }

    public function printPdf(IndividualCounseling $individualCounseling): View
    {
        $individualCounseling->load(['student', 'counselor', 'academicYear', 'documents']);

        $principalName = SchoolSetting::getPrincipalName();
        $principalNip = SchoolSetting::getPrincipalNip();

        $homeroomTeacher = null;
        $classroom = $individualCounseling->student->classrooms()
            ->where('academic_year_id', $individualCounseling->academic_year_id)
            ->with('homeroomTeacher')
            ->first();

        if ($classroom) {
            $homeroomTeacher = $classroom->homeroomTeacher;
        }

        return view('individual-counselings.pdf', [
            'counseling' => $individualCounseling,
            'principalName' => $principalName,
            'principalNip' => $principalNip,
            'homeroomTeacher' => $homeroomTeacher,
        ]);
    }
}
