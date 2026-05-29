@extends('layouts.app')

@section('title', $pageTitle . ' - KonselorKita')

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

<div class="card">
    <div class="card-header flex flex-wrap items-center justify-between gap-4">
        <h6 class="text-lg font-semibold mb-0">{{ $pageTitle }}</h6>
        
        @if(auth()->user()->isSiswa())
            <a href="{{ route('siswa.counseling-requests.create') }}" class="btn btn-primary-600 flex items-center gap-2 !rounded-lg">
                <iconify-icon icon="ic:baseline-plus" class="text-xl"></iconify-icon>
                Ajukan Konseling
            </a>
        @elseif(auth()->user()->isOrangTua())
            <a href="{{ route('orang-tua.counseling-requests.create') }}" class="btn btn-primary-600 flex items-center gap-2 !rounded-lg">
                <iconify-icon icon="ic:baseline-plus" class="text-xl"></iconify-icon>
                Ajukan Konseling Anak
            </a>
        @endif
    </div>

    {{-- Filter Bar for Guru BK --}}
    @if(auth()->user()->isGuruBk())
        <div class="card-body border-b border-neutral-200 dark:border-neutral-700 pb-4">
            <form action="{{ route('guru-bk.counseling-requests.index') }}" method="GET" class="flex flex-wrap items-end gap-3">
                <div class="min-w-[160px]">
                    <label class="form-label text-xs">Status Permohonan</label>
                    <select name="status" class="form-control !rounded-lg">
                        <option value="">Semua Status</option>
                        <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending (Menunggu)</option>
                        <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Approved (Disetujui)</option>
                        <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejected (Ditolak)</option>
                    </select>
                </div>
                <div class="flex gap-2">
                    <button type="submit" class="btn btn-primary-600 !rounded-lg flex items-center gap-2">
                        <iconify-icon icon="ion:search-outline" class="text-xl"></iconify-icon>
                        Filter
                    </button>
                    @if(request()->has('status'))
                        <a href="{{ route('guru-bk.counseling-requests.index') }}" class="btn btn-outline-secondary !rounded-lg">Reset</a>
                    @endif
                </div>
            </form>
        </div>
    @endif

    <div class="card-body">
        <div class="overflow-x-auto">
            <table class="table bordered-table mb-0">
                <thead>
                    <tr>
                        <th scope="col" class="!text-center w-12">No</th>
                        <th scope="col">Siswa</th>
                        @if(!auth()->user()->isSiswa())
                            <th scope="col">Kelas</th>
                        @endif
                        <th scope="col">Guru BK Tujuan</th>
                        <th scope="col" class="!text-center">Tanggal Pengajuan</th>
                        <th scope="col" class="!text-center">Status</th>
                        <th scope="col" class="!text-center w-32">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($requests as $index => $item)
                        <tr>
                            <td class="!text-center">{{ $requests->firstItem() + $index }}</td>
                            <td>
                                <div class="flex flex-col">
                                    <span class="font-medium text-neutral-800 dark:text-neutral-200">{{ $item->student->full_name }}</span>
                                    <span class="text-xs text-neutral-500 dark:text-neutral-400">NIS: {{ $item->student->nis }}</span>
                                </div>
                            </td>
                            @if(!auth()->user()->isSiswa())
                                <td>
                                    @php
                                        $classroom = $item->student->classrooms()->whereHas('academicYear', fn($q) => $q->where('is_active', true))->first();
                                    @endphp
                                    {{ $classroom ? $classroom->name : '-' }}
                                </td>
                            @endif
                            <td>
                                <span class="font-medium">{{ $item->counselor ? $item->counselor->name : 'Semua Guru BK' }}</span>
                            </td>
                            <td class="!text-center text-sm text-neutral-500 dark:text-neutral-400">
                                {{ $item->created_at->format('d M Y, H:i') }}
                            </td>
                            <td class="!text-center">
                                @php
                                    $statusColors = [
                                        'pending' => 'bg-warning-100 text-warning-600 dark:bg-warning-900/30 dark:text-warning-400',
                                        'approved' => 'bg-success-100 text-success-600 dark:bg-success-600/25 dark:text-success-400',
                                        'rejected' => 'bg-danger-100 text-danger-600 dark:bg-danger-600/25 dark:text-danger-400',
                                    ];
                                    $statusLabels = [
                                        'pending' => 'Menunggu',
                                        'approved' => 'Disetujui',
                                        'rejected' => 'Ditolak',
                                    ];
                                @endphp
                                <span class="px-2 py-0.5 rounded text-xs font-medium {{ $statusColors[$item->status] ?? '' }}">
                                    {{ $statusLabels[$item->status] ?? $item->status }}
                                </span>
                            </td>
                            <td class="!text-center">
                                @php
                                    $showRoute = '';
                                    if (auth()->user()->isSiswa()) {
                                        $showRoute = route('siswa.counseling-requests.show', $item);
                                    } elseif (auth()->user()->isOrangTua()) {
                                        $showRoute = route('orang-tua.counseling-requests.show', $item);
                                    } elseif (auth()->user()->isGuruBk()) {
                                        $showRoute = route('guru-bk.counseling-requests.show', $item);
                                    }
                                @endphp
                                <div class="flex items-center justify-center">
                                    <a href="{{ $showRoute }}" class="w-8 h-8 bg-info-50 dark:bg-info-600/25 text-info-600 rounded-lg flex items-center justify-center hover:bg-info-100" title="Lihat Detail">
                                        <iconify-icon icon="solar:eye-bold" class="text-lg"></iconify-icon>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ auth()->user()->isSiswa() ? '6' : '7' }}" class="!text-center py-8">
                                <div class="flex flex-col items-center gap-2 text-neutral-400">
                                    <iconify-icon icon="solar:folder-open-bold" class="text-4xl"></iconify-icon>
                                    <p class="mb-0">Belum ada pengajuan permohonan konseling.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($requests->hasPages())
            <div class="mt-4">
                {{ $requests->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
