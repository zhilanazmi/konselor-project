<?php

namespace App\Http\Controllers;

use App\Enums\UserRole;
use App\Http\Requests\StoreTeacherRequest;
use App\Http\Requests\UpdateTeacherRequest;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class TeacherController extends Controller
{
    public function index(Request $request): View
    {
        $teachers = Teacher::query()
            ->with('user')
            ->when($request->search, fn ($query, $search) => $query->where('full_name', 'like', "%{$search}%")
                ->orWhere('nip', 'like', "%{$search}%"))
            ->orderBy('full_name')
            ->paginate(15)
            ->withQueryString();

        return view('teachers.index', [
            'teachers' => $teachers,
            'pageTitle' => 'Data Guru',
            'activePage' => 'Guru',
        ]);
    }

    public function create(): View
    {
        return view('teachers.create', [
            'pageTitle' => 'Tambah Guru',
            'activePage' => 'Guru',
        ]);
    }

    public function store(StoreTeacherRequest $request): RedirectResponse
    {
        DB::transaction(function () use ($request): void {
            $validated = $request->validated();

            $user = User::query()->create([
                'name' => $validated['full_name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['nip']),
                'role' => UserRole::Guru,
            ]);

            $user->teacher()->create([
                'nip' => $validated['nip'],
                'full_name' => $validated['full_name'],
                'subject' => $validated['subject'] ?? null,
            ]);
        });

        return redirect()
            ->route('admin.teachers.index')
            ->with('success', 'Data guru berhasil ditambahkan.');
    }

    public function edit(Teacher $teacher): View
    {
        $teacher->load('user');

        return view('teachers.edit', [
            'teacher' => $teacher,
            'pageTitle' => 'Edit Guru',
            'activePage' => 'Guru',
        ]);
    }

    public function update(UpdateTeacherRequest $request, Teacher $teacher): RedirectResponse
    {
        $validated = $request->validated();

        DB::transaction(function () use ($teacher, $validated): void {
            $teacher->update([
                'nip' => $validated['nip'],
                'full_name' => $validated['full_name'],
                'subject' => $validated['subject'] ?? null,
            ]);

            $teacher->user->update([
                'name' => $validated['full_name'],
                'email' => $validated['email'],
            ]);
        });

        return redirect()
            ->route('admin.teachers.index')
            ->with('success', 'Data guru berhasil diperbarui.');
    }

    public function destroy(Teacher $teacher): RedirectResponse
    {
        if ($teacher->homeroomClassrooms()->exists() || $teacher->homeroomConsultations()->exists()) {
            return redirect()
                ->route('admin.teachers.index')
                ->with('error', 'Guru tidak dapat dihapus karena masih memiliki data wali kelas atau konsultasi.');
        }

        $teacher->user->delete();

        return redirect()
            ->route('admin.teachers.index')
            ->with('success', 'Data guru berhasil dihapus.');
    }
}
