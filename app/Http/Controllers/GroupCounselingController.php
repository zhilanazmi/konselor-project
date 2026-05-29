<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreGroupCounselingRequest;
use App\Http\Requests\UpdateGroupCounselingRequest;
use App\Models\AcademicYear;
use App\Models\GroupCounseling;
use App\Models\Student;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class GroupCounselingController extends Controller
{
    public function index(Request $request): View
    {
        $counselings = GroupCounseling::query()
            ->with(['counselor', 'academicYear'])
            ->withCount('participants')
            ->when($request->search, fn ($query, $search) => $query->where('topic', 'like', "%{$search}%"))
            ->when($request->status, fn ($query, $status) => $query->where('status', $status))
            ->when($request->academic_year_id, fn ($query, $id) => $query->where('academic_year_id', $id))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $academicYears = AcademicYear::query()->orderByDesc('start_date')->get();

        return view('group-counselings.index', [
            'counselings' => $counselings,
            'academicYears' => $academicYears,
            'pageTitle' => 'Konseling Kelompok',
            'activePage' => 'Konseling Kelompok',
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
            'pageTitle' => 'Tambah Konseling Kelompok',
            'activePage' => 'Konseling Kelompok',
        ]);
    }

    public function store(StoreGroupCounselingRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $studentIds = $validated['student_ids'] ?? [];
        unset($validated['student_ids']);

        $validated['counselor_id'] = auth()->id();

        $counseling = GroupCounseling::query()->create($validated);

        if (! empty($studentIds)) {
            $counseling->participants()->attach($studentIds);
        }

        return redirect()
            ->route('guru-bk.group-counselings.show', $counseling)
            ->with('success', 'Sesi konseling kelompok berhasil dibuat.');
    }

    public function show(GroupCounseling $groupCounseling): View
    {
        $groupCounseling->load(['counselor', 'academicYear', 'participants']);

        $allStudents = Student::query()
            ->orderBy('full_name')
            ->get();

        return view('group-counselings.show', [
            'counseling' => $groupCounseling,
            'allStudents' => $allStudents,
            'pageTitle' => 'Detail Konseling Kelompok',
            'activePage' => 'Konseling Kelompok',
        ]);
    }

    public function edit(GroupCounseling $groupCounseling): View
    {
        $academicYears = AcademicYear::query()->orderByDesc('start_date')->get();

        return view('group-counselings.edit', [
            'counseling' => $groupCounseling,
            'academicYears' => $academicYears,
            'pageTitle' => 'Edit Konseling Kelompok',
            'activePage' => 'Konseling Kelompok',
        ]);
    }

    public function update(UpdateGroupCounselingRequest $request, GroupCounseling $groupCounseling): RedirectResponse
    {
        $groupCounseling->update($request->validated());

        return redirect()
            ->route('guru-bk.group-counselings.show', $groupCounseling)
            ->with('success', 'Data konseling kelompok berhasil diperbarui.');
    }

    public function destroy(GroupCounseling $groupCounseling): RedirectResponse
    {
        $groupCounseling->delete();

        return redirect()
            ->route('guru-bk.group-counselings.index')
            ->with('success', 'Sesi konseling kelompok berhasil dihapus.');
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
}
