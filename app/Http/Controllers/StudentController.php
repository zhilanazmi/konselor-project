<?php

namespace App\Http\Controllers;

use App\Enums\UserRole;
use App\Http\Requests\StoreStudentRequest;
use App\Http\Requests\UpdateStudentRequest;
use App\Models\Classroom;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class StudentController extends Controller
{
    public function index(Request $request): View
    {
        $students = Student::query()
            ->with(['user', 'classrooms.academicYear'])
            ->when($request->search, fn ($query, $search) => $query->where('full_name', 'like', "%{$search}%")
                ->orWhere('nis', 'like', "%{$search}%"))
            ->orderBy('full_name')
            ->paginate(15)
            ->withQueryString();

        return view('students.index', [
            'students' => $students,
            'pageTitle' => 'Data Siswa',
            'activePage' => 'Siswa',
        ]);
    }

    public function create(): View
    {
        return view('students.create', [
            'pageTitle' => 'Tambah Siswa',
            'activePage' => 'Siswa',
        ]);
    }

    public function store(StoreStudentRequest $request): RedirectResponse
    {
        DB::transaction(function () use ($request): void {
            $validated = $request->validated();

            $user = User::query()->create([
                'name' => $validated['full_name'],
                'email' => $validated['nis'].'@siswa.konselorkita.test',
                'password' => Hash::make($validated['nis']),
                'role' => UserRole::Siswa,
            ]);

            $user->student()->create($validated);
        });

        return redirect()
            ->route('admin.students.index')
            ->with('success', 'Data siswa berhasil ditambahkan.');
    }

    public function edit(Student $student): View
    {
        $student->load('classrooms');
        $classrooms = Classroom::query()
            ->with('academicYear')
            ->whereHas('academicYear', fn ($q) => $q->where('is_active', true))
            ->orderBy('name')
            ->get();

        return view('students.edit', [
            'student' => $student,
            'classrooms' => $classrooms,
            'pageTitle' => 'Edit Siswa',
            'activePage' => 'Siswa',
        ]);
    }

    public function update(UpdateStudentRequest $request, Student $student): RedirectResponse
    {
        $validated = $request->validated();
        $classroomId = $request->input('classroom_id');

        DB::transaction(function () use ($student, $validated, $classroomId): void {
            $student->update($validated);
            $student->user->update(['name' => $validated['full_name']]);

            if ($classroomId) {
                $activeClassroomIds = Classroom::query()
                    ->whereHas('academicYear', fn ($q) => $q->where('is_active', true))
                    ->pluck('id')
                    ->toArray();

                $student->classrooms()->detach($activeClassroomIds);
                $student->classrooms()->attach($classroomId);
            }
        });

        return redirect()
            ->route('admin.students.index')
            ->with('success', 'Data siswa berhasil diperbarui.');
    }

    public function destroy(Student $student): RedirectResponse
    {
        if ($student->individualCounselings()->exists() || $student->counselingRequests()->exists()) {
            return redirect()
                ->route('admin.students.index')
                ->with('error', 'Siswa tidak dapat dihapus karena masih memiliki data konseling.');
        }

        $student->user->delete();

        return redirect()
            ->route('admin.students.index')
            ->with('success', 'Data siswa berhasil dihapus.');
    }
}
