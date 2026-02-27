<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreClassroomRequest;
use App\Http\Requests\UpdateClassroomRequest;
use App\Models\AcademicYear;
use App\Models\Classroom;
use App\Models\Student;
use App\Models\Teacher;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ClassroomController extends Controller
{
    public function index(Request $request): View
    {
        $classrooms = Classroom::query()
            ->with(['academicYear', 'homeroomTeacher'])
            ->withCount('students')
            ->when($request->search, fn ($query, $search) => $query->where('name', 'like', "%{$search}%"))
            ->when($request->academic_year_id, fn ($query, $yearId) => $query->where('academic_year_id', $yearId))
            ->orderByDesc('created_at')
            ->paginate(15)
            ->withQueryString();

        $academicYears = AcademicYear::query()->orderByDesc('start_date')->get();

        return view('classrooms.index', [
            'classrooms' => $classrooms,
            'academicYears' => $academicYears,
            'pageTitle' => 'Data Kelas',
            'activePage' => 'Kelas',
        ]);
    }

    public function create(): View
    {
        return view('classrooms.create', [
            'academicYears' => AcademicYear::query()->orderByDesc('start_date')->get(),
            'teachers' => Teacher::query()->orderBy('full_name')->get(),
            'pageTitle' => 'Tambah Kelas',
            'activePage' => 'Kelas',
        ]);
    }

    public function store(StoreClassroomRequest $request): RedirectResponse
    {
        Classroom::query()->create($request->validated());

        return redirect()
            ->route('admin.classrooms.index')
            ->with('success', 'Data kelas berhasil ditambahkan.');
    }

    public function show(Classroom $classroom): View
    {
        $classroom->load(['academicYear', 'homeroomTeacher', 'students' => fn ($q) => $q->orderBy('full_name')]);

        $assignedStudentIds = $classroom->students->pluck('id')->toArray();
        $availableStudents = Student::query()
            ->whereNotIn('id', $assignedStudentIds)
            ->orderBy('full_name')
            ->get();

        return view('classrooms.show', [
            'classroom' => $classroom,
            'availableStudents' => $availableStudents,
            'pageTitle' => 'Detail Kelas: '.$classroom->name,
            'activePage' => 'Kelas',
        ]);
    }

    public function edit(Classroom $classroom): View
    {
        return view('classrooms.edit', [
            'classroom' => $classroom,
            'academicYears' => AcademicYear::query()->orderByDesc('start_date')->get(),
            'teachers' => Teacher::query()->orderBy('full_name')->get(),
            'pageTitle' => 'Edit Kelas',
            'activePage' => 'Kelas',
        ]);
    }

    public function update(UpdateClassroomRequest $request, Classroom $classroom): RedirectResponse
    {
        $classroom->update($request->validated());

        return redirect()
            ->route('admin.classrooms.index')
            ->with('success', 'Data kelas berhasil diperbarui.');
    }

    public function destroy(Classroom $classroom): RedirectResponse
    {
        if ($classroom->students()->exists()) {
            return redirect()
                ->route('admin.classrooms.index')
                ->with('error', 'Kelas tidak dapat dihapus karena masih memiliki siswa terdaftar.');
        }

        $classroom->delete();

        return redirect()
            ->route('admin.classrooms.index')
            ->with('success', 'Data kelas berhasil dihapus.');
    }

    public function addStudents(Request $request, Classroom $classroom): RedirectResponse
    {
        $validated = $request->validate([
            'student_ids' => ['required', 'array', 'min:1'],
            'student_ids.*' => ['exists:students,id'],
        ], [
            'student_ids.required' => 'Pilih minimal satu siswa.',
            'student_ids.min' => 'Pilih minimal satu siswa.',
        ]);

        $classroom->students()->syncWithoutDetaching($validated['student_ids']);

        return redirect()
            ->route('admin.classrooms.show', $classroom)
            ->with('success', 'Siswa berhasil ditambahkan ke kelas.');
    }

    public function removeStudent(Classroom $classroom, Student $student): RedirectResponse
    {
        $classroom->students()->detach($student->id);

        return redirect()
            ->route('admin.classrooms.show', $classroom)
            ->with('success', 'Siswa berhasil dihapus dari kelas.');
    }
}
