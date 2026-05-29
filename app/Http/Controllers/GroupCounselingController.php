<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreGroupCounselingRequest;
use App\Http\Requests\UpdateGroupCounselingRequest;
use App\Models\AcademicYear;
use App\Models\CounselingDocument;
use App\Models\GroupCounseling;
use App\Models\SchoolSetting;
use App\Models\Student;
use App\Services\CounselingDocumentService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class GroupCounselingController extends Controller
{
    public function __construct(private readonly CounselingDocumentService $documentService) {}

    public function index(Request $request): View
    {
        $counselings = GroupCounseling::query()
            ->with(['counselor', 'academicYear'])
            ->withCount('participants')
            ->when($request->search, fn ($query, $search) => $query->where('topic', 'like', "%{$search}%"))
            ->when($request->status, fn ($query, $status) => $query->where('status', $status))
            ->when($request->service_type, fn ($query, $type) => $query->where('service_type', $type))
            ->when($request->academic_year_id, fn ($query, $id) => $query->where('academic_year_id', $id))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $academicYears = AcademicYear::query()->orderByDesc('start_date')->get();

        return view('group-counselings.index', [
            'counselings' => $counselings,
            'academicYears' => $academicYears,
            'pageTitle' => 'Bimbingan Kelompok & Klasikal',
            'activePage' => 'Bimbingan Kelompok',
        ]);
    }

    public function create(): View
    {
        $academicYears = AcademicYear::query()->orderByDesc('start_date')->get();
        $activeAcademicYear = AcademicYear::query()->where('is_active', true)->first();
        $students = Student::query()->orderBy('full_name')->get();

        return view('group-counselings.create', [
            'academicYears' => $academicYears,
            'activeAcademicYear' => $activeAcademicYear,
            'students' => $students,
            'pageTitle' => 'Tambah Bimbingan Kelompok/Klasikal',
            'activePage' => 'Bimbingan Kelompok',
        ]);
    }

    public function store(StoreGroupCounselingRequest $request): RedirectResponse
    {
        $validated = $request->safe()->except(['student_ids', 'documents']);
        $studentIds = $request->validated()['student_ids'] ?? [];

        $validated['counselor_id'] = auth()->id();

        $counseling = GroupCounseling::query()->create($validated);
        $counseling->participants()->attach($studentIds);

        if ($request->hasFile('documents')) {
            $this->documentService->storeDocuments($counseling, $request->file('documents'));
        }

        return redirect()
            ->route('guru-bk.group-counselings.show', $counseling)
            ->with('success', 'Sesi bimbingan berhasil dibuat.');
    }

    public function show(GroupCounseling $groupCounseling): View
    {
        $groupCounseling->load(['counselor', 'academicYear', 'participants', 'documents']);

        $allStudents = Student::query()->orderBy('full_name')->get();
        $principalName = SchoolSetting::getPrincipalName();
        $principalNip = SchoolSetting::getPrincipalNip();

        return view('group-counselings.show', [
            'counseling' => $groupCounseling,
            'allStudents' => $allStudents,
            'principalName' => $principalName,
            'principalNip' => $principalNip,
            'pageTitle' => 'Detail Bimbingan Kelompok/Klasikal',
            'activePage' => 'Bimbingan Kelompok',
        ]);
    }

    public function edit(GroupCounseling $groupCounseling): View
    {
        $groupCounseling->load(['participants', 'documents']);
        $academicYears = AcademicYear::query()->orderByDesc('start_date')->get();
        $students = Student::query()->orderBy('full_name')->get();

        return view('group-counselings.edit', [
            'counseling' => $groupCounseling,
            'academicYears' => $academicYears,
            'students' => $students,
            'pageTitle' => 'Edit Bimbingan Kelompok/Klasikal',
            'activePage' => 'Bimbingan Kelompok',
        ]);
    }

    public function update(UpdateGroupCounselingRequest $request, GroupCounseling $groupCounseling): RedirectResponse
    {
        $groupCounseling->update($request->safe()->except(['documents']));

        if ($request->hasFile('documents')) {
            $this->documentService->storeDocuments($groupCounseling, $request->file('documents'));
        }

        return redirect()
            ->route('guru-bk.group-counselings.show', $groupCounseling)
            ->with('success', 'Data bimbingan berhasil diperbarui.');
    }

    public function destroy(GroupCounseling $groupCounseling): RedirectResponse
    {
        $this->documentService->deleteAllDocuments($groupCounseling);
        $groupCounseling->participants()->detach();
        $groupCounseling->delete();

        return redirect()
            ->route('guru-bk.group-counselings.index')
            ->with('success', 'Sesi bimbingan berhasil dihapus.');
    }

    public function addParticipant(Request $request, GroupCounseling $groupCounseling): RedirectResponse
    {
        $request->validate([
            'student_id' => ['required', 'exists:students,id'],
        ], [
            'student_id.required' => 'Siswa wajib dipilih.',
            'student_id.exists' => 'Siswa tidak ditemukan.',
        ]);

        $alreadyAdded = $groupCounseling->participants()
            ->where('student_id', $request->student_id)
            ->exists();

        if ($alreadyAdded) {
            return back()->with('error', 'Siswa sudah terdaftar sebagai peserta.');
        }

        $groupCounseling->participants()->attach($request->student_id);

        return back()->with('success', 'Peserta berhasil ditambahkan.');
    }

    public function removeParticipant(GroupCounseling $groupCounseling, Student $student): RedirectResponse
    {
        $groupCounseling->participants()->detach($student->id);

        return back()->with('success', 'Peserta berhasil dihapus.');
    }

    public function updateParticipantNotes(Request $request, GroupCounseling $groupCounseling, Student $student): RedirectResponse
    {
        $request->validate([
            'notes' => ['nullable', 'string'],
        ]);

        $groupCounseling->participants()->updateExistingPivot($student->id, [
            'notes' => $request->notes,
        ]);

        return back()->with('success', 'Catatan peserta berhasil diperbarui.');
    }

    public function destroyDocument(GroupCounseling $groupCounseling, CounselingDocument $document): RedirectResponse
    {
        $this->documentService->deleteDocument($document);

        return back()->with('success', 'Dokumen berhasil dihapus.');
    }

    public function printPdf(GroupCounseling $groupCounseling): View
    {
        $groupCounseling->load(['counselor', 'academicYear', 'participants', 'documents']);

        $principalName = SchoolSetting::getPrincipalName();
        $principalNip = SchoolSetting::getPrincipalNip();

        return view('group-counselings.pdf', [
            'counseling' => $groupCounseling,
            'principalName' => $principalName,
            'principalNip' => $principalNip,
        ]);
    }
}
