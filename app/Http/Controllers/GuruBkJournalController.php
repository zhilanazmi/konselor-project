<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreGuruBkJournalRequest;
use App\Http\Requests\UpdateGuruBkJournalRequest;
use App\Models\AcademicYear;
use App\Models\GuruBkJournal;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class GuruBkJournalController extends Controller
{
    /** @var array<string, string> */
    public const ACTIVITY_TYPE_LABELS = [
        'layanan_dasar' => 'Layanan Dasar',
        'layanan_responsif' => 'Layanan Responsif',
        'layanan_perencanaan' => 'Layanan Perencanaan Individual',
        'dukungan_sistem' => 'Dukungan Sistem',
    ];

    public function index(Request $request): View
    {
        $journals = GuruBkJournal::query()
            ->with(['counselor', 'academicYear'])
            ->where('counselor_id', auth()->id())
            ->when($request->search, fn ($query, $search) => $query->where('title', 'like', "%{$search}%"))
            ->when($request->activity_type, fn ($query, $type) => $query->where('activity_type', $type))
            ->when($request->academic_year_id, fn ($query, $id) => $query->where('academic_year_id', $id))
            ->latest('date')
            ->paginate(15)
            ->withQueryString();

        $academicYears = AcademicYear::query()->orderByDesc('start_date')->get();

        return view('guru-bk-journals.index', [
            'journals' => $journals,
            'academicYears' => $academicYears,
            'activityTypeLabels' => self::ACTIVITY_TYPE_LABELS,
            'pageTitle' => 'Jurnal Kegiatan Guru BK',
            'activePage' => 'Jurnal Kegiatan',
        ]);
    }

    public function create(): View
    {
        $academicYears = AcademicYear::query()->orderByDesc('start_date')->get();
        $activeAcademicYear = AcademicYear::query()->where('is_active', true)->first();

        return view('guru-bk-journals.create', [
            'academicYears' => $academicYears,
            'activeAcademicYear' => $activeAcademicYear,
            'activityTypeLabels' => self::ACTIVITY_TYPE_LABELS,
            'pageTitle' => 'Tambah Jurnal Kegiatan',
            'activePage' => 'Jurnal Kegiatan',
        ]);
    }

    public function store(StoreGuruBkJournalRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $validated['counselor_id'] = auth()->id();

        GuruBkJournal::query()->create($validated);

        return redirect()
            ->route('guru-bk.guru-bk-journals.index')
            ->with('success', 'Jurnal kegiatan berhasil disimpan.');
    }

    public function show(GuruBkJournal $guruBkJournal): View
    {
        $guruBkJournal->load(['counselor', 'academicYear']);

        return view('guru-bk-journals.show', [
            'journal' => $guruBkJournal,
            'activityTypeLabels' => self::ACTIVITY_TYPE_LABELS,
            'pageTitle' => 'Detail Jurnal Kegiatan',
            'activePage' => 'Jurnal Kegiatan',
        ]);
    }

    public function edit(GuruBkJournal $guruBkJournal): View
    {
        $academicYears = AcademicYear::query()->orderByDesc('start_date')->get();

        return view('guru-bk-journals.edit', [
            'journal' => $guruBkJournal,
            'academicYears' => $academicYears,
            'activityTypeLabels' => self::ACTIVITY_TYPE_LABELS,
            'pageTitle' => 'Edit Jurnal Kegiatan',
            'activePage' => 'Jurnal Kegiatan',
        ]);
    }

    public function update(UpdateGuruBkJournalRequest $request, GuruBkJournal $guruBkJournal): RedirectResponse
    {
        $guruBkJournal->update($request->validated());

        return redirect()
            ->route('guru-bk.guru-bk-journals.show', $guruBkJournal)
            ->with('success', 'Jurnal kegiatan berhasil diperbarui.');
    }

    public function destroy(GuruBkJournal $guruBkJournal): RedirectResponse
    {
        $guruBkJournal->delete();

        return redirect()
            ->route('guru-bk.guru-bk-journals.index')
            ->with('success', 'Jurnal kegiatan berhasil dihapus.');
    }
}
