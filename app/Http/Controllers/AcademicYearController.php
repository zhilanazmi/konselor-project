<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAcademicYearRequest;
use App\Http\Requests\UpdateAcademicYearRequest;
use App\Models\AcademicYear;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AcademicYearController extends Controller
{
    public function index(Request $request): View
    {
        $academicYears = AcademicYear::query()
            ->when($request->search, fn ($query, $search) => $query->where('name', 'like', "%{$search}%"))
            ->orderByDesc('start_date')
            ->paginate(10)
            ->withQueryString();

        return view('academic-years.index', [
            'academicYears' => $academicYears,
            'pageTitle' => 'Tahun Ajaran',
            'activePage' => 'Tahun Ajaran',
        ]);
    }

    public function create(): View
    {
        return view('academic-years.create', [
            'pageTitle' => 'Tambah Tahun Ajaran',
            'activePage' => 'Tahun Ajaran',
        ]);
    }

    public function store(StoreAcademicYearRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $validated['is_active'] = $request->boolean('is_active');

        if ($validated['is_active']) {
            AcademicYear::query()->where('is_active', true)->update(['is_active' => false]);
        }

        AcademicYear::query()->create($validated);

        return redirect()
            ->route('admin.academic-years.index')
            ->with('success', 'Tahun ajaran berhasil ditambahkan.');
    }

    public function edit(AcademicYear $academicYear): View
    {
        return view('academic-years.edit', [
            'academicYear' => $academicYear,
            'pageTitle' => 'Edit Tahun Ajaran',
            'activePage' => 'Tahun Ajaran',
        ]);
    }

    public function update(UpdateAcademicYearRequest $request, AcademicYear $academicYear): RedirectResponse
    {
        $validated = $request->validated();
        $validated['is_active'] = $request->boolean('is_active');

        if ($validated['is_active'] && ! $academicYear->is_active) {
            AcademicYear::query()->where('is_active', true)->update(['is_active' => false]);
        }

        $academicYear->update($validated);

        return redirect()
            ->route('admin.academic-years.index')
            ->with('success', 'Tahun ajaran berhasil diperbarui.');
    }

    public function destroy(AcademicYear $academicYear): RedirectResponse
    {
        if ($academicYear->classrooms()->exists()) {
            return redirect()
                ->route('admin.academic-years.index')
                ->with('error', 'Tahun ajaran tidak dapat dihapus karena masih memiliki data kelas.');
        }

        $academicYear->delete();

        return redirect()
            ->route('admin.academic-years.index')
            ->with('success', 'Tahun ajaran berhasil dihapus.');
    }
}
