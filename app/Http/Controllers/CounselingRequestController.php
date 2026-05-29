<?php

namespace App\Http\Controllers;

use App\Enums\UserRole;
use App\Http\Requests\StoreCounselingRequestRequest;
use App\Models\AcademicYear;
use App\Models\CounselingRequest;
use App\Models\IndividualCounseling;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class CounselingRequestController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();
        $query = CounselingRequest::query()->with(['student', 'counselor']);

        if ($user->isSiswa()) {
            $student = $user->student;
            $requests = $query->where('student_id', $student?->id)
                ->latest()
                ->paginate(15)
                ->withQueryString();
            $pageTitle = 'Riwayat Pengajuan Konseling';
        } elseif ($user->isOrangTua()) {
            $guardian = $user->guardian;
            $studentIds = $guardian?->students->pluck('id')->toArray() ?? [];
            $requests = $query->whereIn('student_id', $studentIds)
                ->latest()
                ->paginate(15)
                ->withQueryString();
            $pageTitle = 'Riwayat Pengajuan Konseling Anak';
        } elseif ($user->isGuruBk()) {
            $requests = $query->when($request->status, fn ($q, $status) => $q->where('status', $status))
                ->latest()
                ->paginate(15)
                ->withQueryString();
            $pageTitle = 'Daftar Permohonan Konseling';
        } else {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }

        return view('counseling-requests.index', [
            'requests' => $requests,
            'pageTitle' => $pageTitle,
            'activePage' => 'Permohonan Konseling',
        ]);
    }

    public function create(Request $request): View
    {
        $user = $request->user();
        $counselors = User::query()->where('role', UserRole::GuruBk)->orderBy('name')->get();

        if ($user->isSiswa()) {
            $student = $user->student;
            if (! $student) {
                abort(404, 'Profil Siswa Anda tidak ditemukan.');
            }

            return view('counseling-requests.create', [
                'student' => $student,
                'counselors' => $counselors,
                'pageTitle' => 'Ajukan Konseling Baru',
                'activePage' => 'Permohonan Konseling',
            ]);
        } elseif ($user->isOrangTua()) {
            $guardian = $user->guardian;
            if (! $guardian) {
                abort(404, 'Profil Orang Tua Anda tidak ditemukan.');
            }

            $students = $guardian->students;

            return view('counseling-requests.create', [
                'students' => $students,
                'counselors' => $counselors,
                'pageTitle' => 'Ajukan Konseling Anak',
                'activePage' => 'Permohonan Konseling',
            ]);
        }

        abort(403, 'Hanya Siswa atau Orang Tua yang dapat membuat pengajuan konseling.');
    }

    public function store(StoreCounselingRequestRequest $request): RedirectResponse
    {
        $user = $request->user();
        $validated = $request->validated();

        if ($user->isSiswa()) {
            $student = $user->student;
            if (! $student) {
                return back()->with('error', 'Profil Siswa Anda tidak ditemukan.');
            }
            $validated['student_id'] = $student->id;
            $validated['status'] = 'pending';

            CounselingRequest::query()->create($validated);

            return redirect()
                ->route('siswa.counseling-requests.index')
                ->with('success', 'Permohonan konseling berhasil diajukan.');
        } elseif ($user->isOrangTua()) {
            $validated['status'] = 'pending';

            CounselingRequest::query()->create($validated);

            return redirect()
                ->route('orang-tua.counseling-requests.index')
                ->with('success', 'Permohonan konseling untuk anak Anda berhasil diajukan.');
        }

        abort(403, 'Aksi tidak diizinkan.');
    }

    public function show(Request $request, CounselingRequest $counselingRequest): View
    {
        $user = $request->user();
        $counselingRequest->load(['student', 'counselor']);

        // Check Authorization
        if ($user->isSiswa() && $counselingRequest->student_id !== $user->student?->id) {
            abort(403, 'Anda tidak memiliki hak akses untuk melihat pengajuan ini.');
        }

        if ($user->isOrangTua()) {
            $guardian = $user->guardian;
            $hasChild = $guardian?->students()->where('students.id', $counselingRequest->student_id)->exists();
            if (! $hasChild) {
                abort(403, 'Anda tidak memiliki hak akses untuk melihat pengajuan anak lain.');
            }
        }

        if (! $user->isSiswa() && ! $user->isOrangTua() && ! $user->isGuruBk()) {
            abort(403, 'Akses ditolak.');
        }

        $activeAcademicYear = AcademicYear::query()->where('is_active', true)->first();

        return view('counseling-requests.show', [
            'request' => $counselingRequest,
            'activeAcademicYear' => $activeAcademicYear,
            'pageTitle' => 'Detail Permohonan Konseling',
            'activePage' => 'Permohonan Konseling',
        ]);
    }

    public function approve(Request $request, CounselingRequest $counselingRequest): RedirectResponse
    {
        if (! auth()->user()->isGuruBk()) {
            abort(403, 'Aksi hanya diizinkan untuk Guru BK.');
        }

        $validated = $request->validate([
            'scheduled_at' => ['required', 'date'],
            'category' => ['required', 'in:pribadi,sosial,belajar,karir'],
            'admin_notes' => ['nullable', 'string'],
        ], [
            'scheduled_at.required' => 'Tanggal & waktu jadwal sesi wajib ditentukan.',
            'category.required' => 'Kategori bimbingan wajib dipilih.',
            'category.in' => 'Kategori bimbingan tidak valid.',
        ]);

        $activeAcademicYear = AcademicYear::query()->where('is_active', true)->first();
        if (! $activeAcademicYear) {
            return back()->with('error', 'Tidak dapat menyetujui permohonan karena tidak ada Tahun Ajaran aktif.');
        }

        DB::transaction(function () use ($validated, $counselingRequest, $activeAcademicYear): void {
            // Create the IndividualCounseling session automatically
            IndividualCounseling::query()->create([
                'academic_year_id' => $activeAcademicYear->id,
                'counselor_id' => auth()->id(),
                'student_id' => $counselingRequest->student_id,
                'scheduled_at' => $validated['scheduled_at'],
                'status' => 'scheduled',
                'category' => $validated['category'],
                'problem_description' => $counselingRequest->reason,
            ]);

            // Update status and save notes
            $counselingRequest->update([
                'status' => 'approved',
                'counselor_id' => auth()->id(),
                'admin_notes' => $validated['admin_notes'] ?? 'Permohonan disetujui.',
            ]);
        });

        return redirect()
            ->route('guru-bk.counseling-requests.show', $counselingRequest)
            ->with('success', 'Permohonan konseling berhasil disetujui dan telah dijadwalkan.');
    }

    public function reject(Request $request, CounselingRequest $counselingRequest): RedirectResponse
    {
        if (! auth()->user()->isGuruBk()) {
            abort(403, 'Aksi hanya diizinkan untuk Guru BK.');
        }

        $validated = $request->validate([
            'admin_notes' => ['required', 'string', 'min:5'],
        ], [
            'admin_notes.required' => 'Catatan penolakan wajib diisi.',
            'admin_notes.min' => 'Catatan penolakan minimal 5 karakter.',
        ]);

        $counselingRequest->update([
            'status' => 'rejected',
            'counselor_id' => auth()->id(),
            'admin_notes' => $validated['admin_notes'],
        ]);

        return redirect()
            ->route('guru-bk.counseling-requests.show', $counselingRequest)
            ->with('success', 'Permohonan konseling berhasil ditolak.');
    }
}
