@extends('layouts.app')

@section('title', 'Jurnal Kegiatan Guru BK - KonselorKita')

@section('content')

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
        <h6 class="text-lg font-semibold mb-0">Jurnal Kegiatan BK</h6>
        <a href="{{ route('guru-bk.guru-bk-journals.create') }}" class="btn btn-primary-600 flex items-center gap-2 !rounded-lg">
            <iconify-icon icon="ic:baseline-plus" class="text-xl"></iconify-icon>
            Tambah Kegiatan
        </a>
    </div>

    {{-- Filter Bar --}}
    <div class="card-body border-b border-neutral-200 dark:border-neutral-700 pb-4">
        <form action="{{ route('guru-bk.guru-bk-journals.index') }}" method="GET" class="flex flex-wrap items-end gap-3">
            <div class="flex-1 min-w-[200px]">
                <label class="form-label text-xs">Cari Judul</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Judul kegiatan..." class="form-control !rounded-lg">
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
            <div class="min-w-[190px]">
                <label class="form-label text-xs">Jenis Kegiatan</label>
                <select name="activity_type" class="form-control !rounded-lg">
                    <option value="">Semua Jenis</option>
                    @foreach($activityTypeLabels as $value => $label)
                        <option value="{{ $value }}" {{ request('activity_type') === $value ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex gap-2">
                <button type="submit" class="btn btn-primary-600 !rounded-lg flex items-center gap-2">
                    <iconify-icon icon="ion:search-outline" class="text-xl"></iconify-icon>
                    Filter
                </button>
                @if(request()->hasAny(['search', 'activity_type', 'academic_year_id']))
                    <a href="{{ route('guru-bk.guru-bk-journals.index') }}" class="btn btn-outline-secondary !rounded-lg">Reset</a>
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
                        <th scope="col">Tanggal</th>
                        <th scope="col">Jenis Kegiatan</th>
                        <th scope="col">Judul Kegiatan</th>
                        <th scope="col">Sasaran</th>
                        <th scope="col" class="!text-center">Durasi</th>
                        <th scope="col" class="!text-center w-28">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $typeColors = [
                            'layanan_dasar' => 'bg-blue-100 text-blue-600 dark:bg-blue-900/30 dark:text-blue-400',
                            'layanan_responsif' => 'bg-amber-100 text-amber-600 dark:bg-amber-900/30 dark:text-amber-400',
                            'layanan_perencanaan' => 'bg-purple-100 text-purple-600 dark:bg-purple-900/30 dark:text-purple-400',
                            'dukungan_sistem' => 'bg-green-100 text-green-600 dark:bg-green-900/30 dark:text-green-400',
                        ];
                    @endphp
                    @forelse($journals as $index => $journal)
                        <tr>
                            <td class="!text-center">{{ $journals->firstItem() + $index }}</td>
                            <td class="text-sm">{{ $journal->date->format('d M Y') }}</td>
                            <td>
                                <span class="px-2 py-0.5 rounded text-xs font-medium {{ $typeColors[$journal->activity_type] ?? '' }}">
                                    {{ $activityTypeLabels[$journal->activity_type] ?? $journal->activity_type }}
                                </span>
                            </td>
                            <td>
                                <span class="font-medium">{{ $journal->title }}</span>
                                @if($journal->location)
                                    <p class="text-xs text-neutral-400 mb-0 mt-0.5">
                                        <iconify-icon icon="solar:map-point-bold" class="text-xs"></iconify-icon>
                                        {{ $journal->location }}
                                    </p>
                                @endif
                            </td>
                            <td class="text-sm text-neutral-500 dark:text-neutral-400">
                                {{ $journal->target_group ?: '-' }}
                            </td>
                            <td class="!text-center text-sm">
                                @if($journal->duration_minutes)
                                    {{ $journal->duration_minutes }} menit
                                @else
                                    -
                                @endif
                            </td>
                            <td class="!text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('guru-bk.guru-bk-journals.show', $journal) }}" class="w-8 h-8 bg-info-50 dark:bg-info-600/25 text-info-600 rounded-lg flex items-center justify-center hover:bg-info-100" title="Detail">
                                        <iconify-icon icon="solar:eye-bold" class="text-lg"></iconify-icon>
                                    </a>
                                    <a href="{{ route('guru-bk.guru-bk-journals.edit', $journal) }}" class="w-8 h-8 bg-primary-50 dark:bg-primary-600/25 text-primary-600 rounded-lg flex items-center justify-center hover:bg-primary-100" title="Edit">
                                        <iconify-icon icon="solar:pen-bold" class="text-lg"></iconify-icon>
                                    </a>
                                    <form action="{{ route('guru-bk.guru-bk-journals.destroy', $journal) }}" method="POST" onsubmit="return confirm('Hapus jurnal kegiatan ini?')">
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
                                    <iconify-icon icon="solar:notebook-bold" class="text-4xl"></iconify-icon>
                                    <p class="mb-0">Belum ada jurnal kegiatan.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($journals->hasPages())
            <div class="mt-4">
                {{ $journals->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
