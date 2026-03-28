<?php

namespace App\Http\Controllers;

use App\Enums\UserRole;
use App\Http\Requests\StoreGuardianRequest;
use App\Http\Requests\UpdateGuardianRequest;
use App\Models\Guardian;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class GuardianController extends Controller
{
    public function index(Request $request): View
    {
        $guardians = Guardian::query()
            ->with(['user', 'students'])
            ->when($request->search, fn ($query, $search) => $query->where('full_name', 'like', "%{$search}%"))
            ->orderBy('full_name')
            ->paginate(15)
            ->withQueryString();

        return view('guardians.index', [
            'guardians' => $guardians,
            'pageTitle' => 'Data Orang Tua',
            'activePage' => 'Orang Tua',
        ]);
    }

    public function create(): View
    {
        $students = Student::query()->orderBy('full_name')->get(['id', 'full_name', 'nis']);

        return view('guardians.create', [
            'students' => $students,
            'pageTitle' => 'Tambah Orang Tua',
            'activePage' => 'Orang Tua',
        ]);
    }

    public function store(StoreGuardianRequest $request): RedirectResponse
    {
        DB::transaction(function () use ($request): void {
            $validated = $request->validated();

            $user = User::query()->create([
                'name' => $validated['full_name'],
                'email' => $validated['email'],
                'password' => Hash::make('orangtua123'),
                'role' => UserRole::OrangTua,
            ]);

            $guardian = $user->guardian()->create([
                'full_name' => $validated['full_name'],
                'phone' => $validated['phone'] ?? null,
                'address' => $validated['address'] ?? null,
                'occupation' => $validated['occupation'] ?? null,
            ]);

            $this->syncStudents($guardian, $validated['students'] ?? []);
        });

        return redirect()
            ->route('admin.guardians.index')
            ->with('success', 'Data orang tua berhasil ditambahkan.');
    }

    public function edit(Guardian $guardian): View
    {
        $guardian->load(['user', 'students']);
        $students = Student::query()->orderBy('full_name')->get(['id', 'full_name', 'nis']);

        return view('guardians.edit', [
            'guardian' => $guardian,
            'students' => $students,
            'pageTitle' => 'Edit Orang Tua',
            'activePage' => 'Orang Tua',
        ]);
    }

    public function update(UpdateGuardianRequest $request, Guardian $guardian): RedirectResponse
    {
        $validated = $request->validated();

        DB::transaction(function () use ($guardian, $validated): void {
            $guardian->update([
                'full_name' => $validated['full_name'],
                'phone' => $validated['phone'] ?? null,
                'address' => $validated['address'] ?? null,
                'occupation' => $validated['occupation'] ?? null,
            ]);

            $guardian->user->update([
                'name' => $validated['full_name'],
                'email' => $validated['email'],
            ]);

            $this->syncStudents($guardian, $validated['students'] ?? []);
        });

        return redirect()
            ->route('admin.guardians.index')
            ->with('success', 'Data orang tua berhasil diperbarui.');
    }

    public function destroy(Guardian $guardian): RedirectResponse
    {
        if ($guardian->parentConsultations()->exists()) {
            return redirect()
                ->route('admin.guardians.index')
                ->with('error', 'Orang tua tidak dapat dihapus karena masih memiliki data konsultasi.');
        }

        $guardian->user->delete();

        return redirect()
            ->route('admin.guardians.index')
            ->with('success', 'Data orang tua berhasil dihapus.');
    }

    /**
     * @param  array<int, array{student_id: int, relationship: string}>  $studentsData
     */
    private function syncStudents(Guardian $guardian, array $studentsData): void
    {
        $syncData = [];
        foreach ($studentsData as $entry) {
            if (! empty($entry['student_id'])) {
                $syncData[$entry['student_id']] = ['relationship' => $entry['relationship']];
            }
        }

        $guardian->students()->sync($syncData);
    }
}
