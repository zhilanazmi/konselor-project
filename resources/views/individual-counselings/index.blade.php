@extends('layouts.app')

@section('title', 'Konseling Individual - KonselorKita')

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
        <h6 class="text-lg font-semibold mb-0">Daftar Konseling Individual</h6>
        <a href="{{ route('guru-bk.individual-counselings.create') }}" class="btn btn-primary-600 flex items-center gap-2 !rounded-lg">
            <iconify-icon icon="ic:baseline-plus" class="text-xl"></iconify-icon>
            Tambah Sesi
        </a>
    </div>

    {{-- Filter Bar --}}
    <div class="card-body border-b border-neutral-200 dark:border-neutral-700 pb-4">
        <form action="{{ route('guru-bk.individual-counselings.index') }}" method="GET" class="flex flex-wrap items-end gap-3">
            <div class="flex-1 min-w-[180px]">
                <label class="form-label text-xs">Cari Siswa</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Nama siswa..." class="form-control !rounded-lg">
            </div>
            <div class="min-w-[150px]">
                <label class="form-label text-xs">Tahun Ajaran</label>
                <select name="academic_year_id" class="form-control !rounded-lg">
                    <option value="">Semua Tahun</option>
                    @foreach($academicYears as $year)
                        <option value="{{ $year->id }}" {{ request('academic_year_id') == $year->id ? 'selected' : '' }}>
                            {{ $year->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="min-w-[140px]">
                <label class="form-label text-xs">Status</label>
                <select name="status" class="form-control !rounded-lg">
                    <option value="">Semua Status</option>
                    <option value="scheduled" {{ request('status') === 'scheduled' ? 'selected' : '' }}>Scheduled</option>
                    <option value="ongoing" {{ request('status') === 'ongoing' ? 'selected' : '' }}>Ongoing</option>
                    <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
                    <option value="followed_up" {{ request('status') === 'followed_up' ? 'selected' : '' }}>Followed Up</option>
                </select>
            </div>
            <div class="min-w-[140px]">
                <label class="form-label text-xs">Kategori</label>
                <select name="category" class="form-control !rounded-lg">
                    <option value="">Semua Kategori</option>
                    <option value="pribadi" {{ request('category') === 'pribadi' ? 'selected' : '' }}>Pribadi</option>
                    <option value="sosial" {{ request('category') === 'sosial' ? 'selected' : '' }}>Sosial</option>
                    <option value="belajar" {{ request('category') === 'belajar' ? 'selected' : '' }}>Belajar</option>
                    <option value="karir" {{ request('category') === 'karir' ? 'selected' : '' }}>Karir</option>
                </select>
            </div>
            <div class="flex gap-2">
                <button type="submit" class="btn btn-primary-600 !rounded-lg flex items-center gap-2">
                    <iconify-icon icon="ion:search-outline" class="text-xl"></iconify-icon>
                    Filter
                </button>
                @if(request()->hasAny(['search', 'status', 'category', 'academic_year_id']))
                    <a href="{{ route('guru-bk.individual-counselings.index') }}" class="btn btn-outline-secondary !rounded-lg">Reset</a>
                @endif
            </div>
        </form>
    </div>

    <div class="card-body">
        <div class="overflow-x-auto">
            <table class="table bordered-table mb-0">
                <thead>
                    <tr>
                        <th scope="col" class="!text-center w-12">No</th>
                        <th scope="col">Siswa</th>
                        <th scope="col">Tahun Ajaran</th>
                        <th scope="col" class="!text-center">Kategori</th>
                        <th scope="col" class="!text-center">Status</th>
                        <th scope="col">Jadwal</th>
                        <th scope="col" class="!text-center w-36">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($counselings as $index => $counseling)
                        <tr>
                            <td class="!text-center">{{ $counselings->firstItem() + $index }}</td>
                            <td>
                                <span class="font-medium">{{ $counseling->student->full_name }}</span>
                            </td>
                            <td>{{ $counseling->academicYear->name }}</td>
                            <td class="!text-center">
                                @php
                                    $categoryColors = [
                                        'pribadi' => 'bg-purple-100 text-purple-600 dark:bg-purple-900/30 dark:text-purple-400',
                                        'sosial' => 'bg-blue-100 text-blue-600 dark:bg-blue-900/30 dark:text-blue-400',
                                        'belajar' => 'bg-amber-100 text-amber-600 dark:bg-amber-900/30 dark:text-amber-400',
                                        'karir' => 'bg-green-100 text-green-600 dark:bg-green-900/30 dark:text-green-400',
                                    ];
                                @endphp
                                <span class="px-2 py-0.5 rounded text-xs font-medium {{ $categoryColors[$counseling->category] ?? '' }}">
                                    {{ ucfirst($counseling->category) }}
                                </span>
                            </td>
                            <td class="!text-center">
                                @php
                                    $statusColors = [
                                        'scheduled' => 'bg-sky-100 text-sky-600 dark:bg-sky-900/30 dark:text-sky-400',
                                        'ongoing' => 'bg-amber-100 text-amber-600 dark:bg-amber-900/30 dark:text-amber-400',
                                        'completed' => 'bg-success-100 text-success-600 dark:bg-success-600/25 dark:text-success-400',
                                        'followed_up' => 'bg-primary-100 text-primary-600 dark:bg-primary-600/25 dark:text-primary-400',
                                    ];
                                    $statusLabels = [
                                        'scheduled' => 'Dijadwalkan',
                                        'ongoing' => 'Berlangsung',
                                        'completed' => 'Selesai',
                                        'followed_up' => 'Tindak Lanjut',
                                    ];
                                @endphp
                                <span class="px-2 py-0.5 rounded text-xs font-medium {{ $statusColors[$counseling->status] ?? '' }}">
                                    {{ $statusLabels[$counseling->status] ?? $counseling->status }}
                                </span>
                            </td>
                            <td class="text-sm text-neutral-500 dark:text-neutral-400">
                                {{ $counseling->scheduled_at->format('d M Y, H:i') }}
                            </td>
                            <td class="!text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('guru-bk.individual-counselings.show', $counseling) }}" class="w-8 h-8 bg-info-50 dark:bg-info-600/25 text-info-600 rounded-lg flex items-center justify-center hover:bg-info-100" title="Detail">
                                        <iconify-icon icon="solar:eye-bold" class="text-lg"></iconify-icon>
                                    </a>
                                    <a href="{{ route('guru-bk.individual-counselings.edit', $counseling) }}" class="w-8 h-8 bg-primary-50 dark:bg-primary-600/25 text-primary-600 rounded-lg flex items-center justify-center hover:bg-primary-100" title="Edit">
                                        <iconify-icon icon="solar:pen-bold" class="text-lg"></iconify-icon>
                                    </a>
                                    <form action="{{ route('guru-bk.individual-counselings.destroy', $counseling) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus sesi konseling ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="w-8 h-8 bg-danger-50 dark:bg-danger-600/25 text-danger-600 rounded-lg flex items-center justify-center hover:bg-danger-100" title="Hapus">
                                            <iconify-icon icon="solar:trash-bin-trash-bold" class="text-lg"></iconify-icon>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="!text-center py-8">
                                <div class="flex flex-col items-center gap-2 text-neutral-400">
                                    <iconify-icon icon="solar:folder-open-bold" class="text-4xl"></iconify-icon>
                                    <p class="mb-0">Belum ada data konseling individual.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($counselings->hasPages())
            <div class="mt-4">
                {{ $counselings->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
