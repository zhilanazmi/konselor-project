@extends('layouts.app')

@section('title', 'Detail Permohonan Konseling - KonselorKita')

@section('content')

{{-- Flash Messages --}}
@if(session('success'))
    <div class="bg-success-100 dark:bg-success-600/25 text-success-600 dark:text-success-400 px-4 py-3 rounded-lg mb-4 flex items-center gap-2">
        <iconify-icon icon="solar:check-circle-bold" class="text-xl"></iconify-icon>
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="bg-danger-100 dark:bg-danger-600/25 text-danger-600 dark:text-danger-400 px-4 py-3 rounded-lg mb-4 flex items-center gap-2">
        <iconify-icon icon="solar:danger-circle-bold" class="text-xl"></iconify-icon>
        {{ session('error') }}
    </div>
@endif

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    {{-- Left Side: Request Details --}}
    <div class="lg:col-span-2 flex flex-col gap-6">
        
        {{-- Card Details --}}
        <div class="card">
            <div class="card-header flex items-center justify-between">
                <h6 class="text-lg font-semibold mb-0">Informasi Permohonan</h6>
                @php
                    $backRoute = '';
                    if (auth()->user()->isSiswa()) {
                        $backRoute = route('siswa.counseling-requests.index');
                    } elseif (auth()->user()->isOrangTua()) {
                        $backRoute = route('orang-tua.counseling-requests.index');
                    } elseif (auth()->user()->isGuruBk()) {
                        $backRoute = route('guru-bk.counseling-requests.index');
                    }
                @endphp
                <a href="{{ $backRoute }}" class="btn btn-sm btn-outline-secondary !rounded-lg flex items-center gap-1">
                    <iconify-icon icon="solar:arrow-left-bold" class="text-lg"></iconify-icon>
                    Kembali
                </a>
            </div>

            <div class="card-body">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                    <div>
                        <span class="text-xs text-neutral-400 dark:text-neutral-500 uppercase block mb-1">Nama Siswa</span>
                        <span class="text-sm font-semibold text-neutral-800 dark:text-neutral-200">{{ $request->student->full_name }}</span>
                    </div>
                    <div>
                        <span class="text-xs text-neutral-400 dark:text-neutral-500 uppercase block mb-1">NIS</span>
                        <span class="text-sm font-semibold text-neutral-800 dark:text-neutral-200">{{ $request->student->nis }}</span>
                    </div>
                    <div>
                        <span class="text-xs text-neutral-400 dark:text-neutral-500 uppercase block mb-1">Kelas Aktif</span>
                        @php
                            $classroom = $request->student->classrooms()->whereHas('academicYear', fn($q) => $q->where('is_active', true))->first();
                        @endphp
                        <span class="text-sm font-semibold text-neutral-800 dark:text-neutral-200">{{ $classroom ? $classroom->name : '-' }}</span>
                    </div>
                    <div>
                        <span class="text-xs text-neutral-400 dark:text-neutral-500 uppercase block mb-1">Tanggal Pengajuan</span>
                        <span class="text-sm font-semibold text-neutral-800 dark:text-neutral-200">{{ $request->created_at->format('d M Y, H:i') }}</span>
                    </div>
                </div>

                <div class="border-t border-neutral-100 dark:border-neutral-800 pt-4">
                    <span class="text-xs text-neutral-400 dark:text-neutral-500 uppercase block mb-2">Alasan / Kendala yang Dialami</span>
                    <div class="bg-neutral-50 dark:bg-neutral-800/50 p-4 rounded-lg text-sm text-neutral-700 dark:text-neutral-300 leading-relaxed whitespace-pre-wrap">{{ $request->reason }}</div>
                </div>
            </div>
        </div>

        {{-- Guru BK: Approve & Reject Forms --}}
        @if(auth()->user()->isGuruBk() && $request->status === 'pending')
            
            {{-- Approve Form Card --}}
            <div id="approve-card" class="card hidden border border-success-200 dark:border-success-900/50 bg-success-50/10 dark:bg-success-900/5">
                <div class="card-header border-b border-success-100 dark:border-success-900/20 flex items-center justify-between">
                    <h6 class="text-md font-semibold text-success-700 dark:text-success-400 mb-0 flex items-center gap-2">
                        <iconify-icon icon="solar:check-circle-bold" class="text-xl"></iconify-icon>
                        Setujui & Jadwalkan Sesi Konseling
                    </h6>
                    <button type="button" class="text-neutral-400 hover:text-neutral-600" onclick="toggleForm('approve', false)">
                        <iconify-icon icon="radix-icons:cross-2" class="text-lg"></iconify-icon>
                    </button>
                </div>
                <div class="card-body">
                    <form action="{{ route('guru-bk.counseling-requests.approve', $request) }}" method="POST" class="flex flex-col gap-4">
                        @csrf
                        @if(!$activeAcademicYear)
                            <div class="text-danger-600 text-sm">Peringatan: Tidak ada Tahun Ajaran yang aktif di sistem. Harap hubungi Admin.</div>
                        @endif

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="form-label font-medium">Kategori Masalah</label>
                                <select name="category" class="form-control !rounded-lg" required>
                                    <option value="pribadi">Pribadi</option>
                                    <option value="sosial">Sosial</option>
                                    <option value="belajar">Belajar</option>
                                    <option value="karir">Karir</option>
                                </select>
                            </div>
                            <div>
                                <label class="form-label font-medium">Tanggal & Waktu Pertemuan</label>
                                <input type="datetime-local" name="scheduled_at" class="form-control !rounded-lg" required>
                            </div>
                        </div>

                        <div>
                            <label class="form-label font-medium">Catatan / Rekomendasi Awal (Opsional)</label>
                            <textarea name="admin_notes" rows="3" placeholder="Contoh: Silakan datang ke ruang BK pada jam istirahat kedua..." class="form-control !rounded-lg"></textarea>
                        </div>

                        <div class="flex justify-end gap-2 mt-2">
                            <button type="button" class="btn btn-outline-secondary btn-sm !rounded-lg" onclick="toggleForm('approve', false)">Batal</button>
                            <button type="submit" class="btn btn-success-600 btn-sm !rounded-lg flex items-center gap-1" {{ !$activeAcademicYear ? 'disabled' : '' }}>
                                <iconify-icon icon="solar:check-circle-bold" class="text-md"></iconify-icon>
                                Setujui & Simpan Jadwal
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Reject Form Card --}}
            <div id="reject-card" class="card hidden border border-danger-200 dark:border-danger-900/50 bg-danger-50/10 dark:bg-danger-900/5">
                <div class="card-header border-b border-danger-100 dark:border-danger-900/20 flex items-center justify-between">
                    <h6 class="text-md font-semibold text-danger-700 dark:text-danger-400 mb-0 flex items-center gap-2">
                        <iconify-icon icon="solar:danger-circle-bold" class="text-xl"></iconify-icon>
                        Tolak Permohonan Konseling
                    </h6>
                    <button type="button" class="text-neutral-400 hover:text-neutral-600" onclick="toggleForm('reject', false)">
                        <iconify-icon icon="radix-icons:cross-2" class="text-lg"></iconify-icon>
                    </button>
                </div>
                <div class="card-body">
                    <form action="{{ route('guru-bk.counseling-requests.reject', $request) }}" method="POST" class="flex flex-col gap-4">
                        @csrf
                        <div>
                            <label class="form-label font-medium">Alasan Penolakan / Catatan Alternatif</label>
                            <textarea name="admin_notes" rows="4" placeholder="Tuliskan alasan penolakan atau tawaran jadwal lain secara jelas..." class="form-control !rounded-lg" required></textarea>
                            <span class="text-xs text-neutral-400 dark:text-neutral-500 mt-1 block">Minimal 5 karakter.</span>
                        </div>

                        <div class="flex justify-end gap-2 mt-2">
                            <button type="button" class="btn btn-outline-secondary btn-sm !rounded-lg" onclick="toggleForm('reject', false)">Batal</button>
                            <button type="submit" class="btn btn-danger-600 btn-sm !rounded-lg flex items-center gap-1">
                                <iconify-icon icon="solar:danger-circle-bold" class="text-md"></iconify-icon>
                                Tolak Permohonan
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        @endif

    </div>

    {{-- Right Side: Status Info & Actions --}}
    <div class="flex flex-col gap-6">
        
        {{-- Status Block --}}
        <div class="card">
            <div class="card-header">
                <h6 class="text-md font-semibold mb-0">Status Pengajuan</h6>
            </div>
            <div class="card-body flex flex-col gap-4">
                
                {{-- Status Badge --}}
                <div class="flex items-center gap-3">
                    @php
                        $statusColors = [
                            'pending' => 'bg-warning-100 text-warning-600 dark:bg-warning-900/30 dark:text-warning-400',
                            'approved' => 'bg-success-100 text-success-600 dark:bg-success-600/25 dark:text-success-400',
                            'rejected' => 'bg-danger-100 text-danger-600 dark:bg-danger-600/25 dark:text-danger-400',
                        ];
                        $statusLabels = [
                            'pending' => 'Menunggu Persetujuan',
                            'approved' => 'Disetujui',
                            'rejected' => 'Ditolak',
                        ];
                    @endphp
                    <span class="px-3 py-1.5 rounded-full text-xs font-semibold {{ $statusColors[$request->status] ?? '' }}">
                        {{ $statusLabels[$request->status] ?? $request->status }}
                    </span>
                </div>

                {{-- Admin / Counselor Notes --}}
                @if($request->status === 'approved')
                    <div class="border-t border-neutral-100 dark:border-neutral-800 pt-3">
                        <span class="text-xs text-neutral-400 block mb-1">Diproses Oleh</span>
                        <span class="text-sm font-semibold text-neutral-800 dark:text-neutral-200">{{ $request->counselor->name }}</span>
                    </div>
                    <div class="border-t border-neutral-100 dark:border-neutral-800 pt-3">
                        <span class="text-xs text-neutral-400 block mb-1">Catatan Guru BK</span>
                        <p class="text-sm text-neutral-700 dark:text-neutral-300 leading-normal bg-neutral-50 dark:bg-neutral-800/30 p-3 rounded-lg">{{ $request->admin_notes ?? '-' }}</p>
                    </div>
                @elseif($request->status === 'rejected')
                    <div class="border-t border-neutral-100 dark:border-neutral-800 pt-3">
                        <span class="text-xs text-neutral-400 block mb-1">Ditolak Oleh</span>
                        <span class="text-sm font-semibold text-neutral-800 dark:text-neutral-200">{{ $request->counselor->name }}</span>
                    </div>
                    <div class="border-t border-neutral-100 dark:border-neutral-800 pt-3">
                        <span class="text-xs text-danger-500 block mb-1">Alasan Penolakan</span>
                        <p class="text-sm text-danger-600 dark:text-danger-400 leading-normal bg-danger-50/50 dark:bg-danger-900/10 p-3 rounded-lg">{{ $request->admin_notes }}</p>
                    </div>
                @endif

                {{-- Action Buttons for Guru BK --}}
                @if(auth()->user()->isGuruBk() && $request->status === 'pending')
                    <div class="border-t border-neutral-100 dark:border-neutral-800 pt-4 flex flex-col gap-2">
                        <button type="button" class="btn btn-success-600 w-full !rounded-lg flex items-center justify-center gap-2" onclick="toggleForm('approve', true)">
                            <iconify-icon icon="solar:check-circle-bold" class="text-lg"></iconify-icon>
                            Setujui & Jadwalkan
                        </button>
                        <button type="button" class="btn btn-danger-600 w-full !rounded-lg flex items-center justify-center gap-2" onclick="toggleForm('reject', true)">
                            <iconify-icon icon="solar:danger-circle-bold" class="text-lg"></iconify-icon>
                            Tolak Permohonan
                        </button>
                    </div>
                @endif

            </div>
        </div>

    </div>

</div>

<script>
    function toggleForm(type, show) {
        const approveCard = document.getElementById('approve-card');
        const rejectCard = document.getElementById('reject-card');

        if (type === 'approve') {
            if (show) {
                approveCard.classList.remove('hidden');
                rejectCard.classList.add('hidden');
                // Scroll to the card
                approveCard.scrollIntoView({ behavior: 'smooth', block: 'end' });
            } else {
                approveCard.classList.add('hidden');
            }
        } else if (type === 'reject') {
            if (show) {
                rejectCard.classList.remove('hidden');
                approveCard.classList.add('hidden');
                // Scroll to the card
                rejectCard.scrollIntoView({ behavior: 'smooth', block: 'end' });
            } else {
                rejectCard.classList.add('hidden');
            }
        }
    }
</script>
@endsection
