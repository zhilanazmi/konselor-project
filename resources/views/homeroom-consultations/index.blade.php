@extends('layouts.app')

@section('title', 'Konsultasi Wali Kelas - KonselorKita')

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
        <h6 class="text-lg font-semibold mb-0">Daftar Konsultasi Wali Kelas</h6>
        <a href="{{ route('guru-bk.homeroom-consultations.create') }}" class="btn btn-primary-600 flex items-center gap-2 !rounded-lg">
            <iconify-icon icon="ic:baseline-plus" class="text-xl"></iconify-icon>
            Tambah Konsultasi
        </a>
    </div>

    {{-- Filter Bar --}}
    <div class="card-body border-b border-neutral-200 dark:border-neutral-700 pb-4">
        <form action="{{ route('guru-bk.homeroom-consultations.index') }}" method="GET" class="flex flex-wrap items-end gap-3">
            <div class="flex-1 min-w-[180px]">
                <label class="form-label text-xs">Cari Siswa / Wali Kelas</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Nama siswa atau wali kelas..." class="form-control !rounded-lg">
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
            <div class="min-w-[160px]">
                <label class="form-label text-xs">Wali Kelas</label>
                <select name="teacher_id" class="form-control !rounded-lg">
                    <option value="">Semua Wali Kelas</option>
                    @foreach($teachers as $teacher)
                        <option value="{{ $teacher->id }}" {{ request('teacher_id') == $teacher->id ? 'selected' : '' }}>
                            {{ $teacher->full_name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="flex gap-2">
                <button type="submit" class="btn btn-primary-600 !rounded-lg flex items-center gap-2">
                    <iconify-icon icon="ion:search-outline" class="text-xl"></iconify-icon>
                    Filter
                </button>
                @if(request()->hasAny(['search', 'academic_year_id', 'teacher_id']))
                    <a href="{{ route('guru-bk.homeroom-consultations.index') }}" class="btn btn-outline-secondary !rounded-lg">Reset</a>
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
                        <th scope="col">Wali Kelas</th>
                        <th scope="col">Topik</th>
                        <th scope="col">Tahun Ajaran</th>
                        <th scope="col">Tanggal</th>
                        <th scope="col" class="!text-center w-32">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($consultations as $index => $consultation)
                        <tr>
                            <td class="!text-center">{{ $consultations->firstItem() + $index }}</td>
                            <td>
                                <span class="font-medium">{{ $consultation->student->full_name }}</span>
                                <p class="text-xs text-neutral-400 mb-0">{{ $consultation->student->nis }}</p>
                            </td>
                            <td>{{ $consultation->teacher->full_name }}</td>
                            <td class="max-w-[220px]">
                                <p class="mb-0 text-sm truncate" title="{{ $consultation->topic }}">{{ $consultation->topic }}</p>
                            </td>
                            <td>{{ $consultation->academicYear->name }}</td>
                            <td class="text-sm text-neutral-500 dark:text-neutral-400">
                                {{ $consultation->consultation_date->format('d M Y') }}
                            </td>
                            <td class="!text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('guru-bk.homeroom-consultations.show', $consultation) }}" class="w-8 h-8 bg-info-50 dark:bg-info-600/25 text-info-600 rounded-lg flex items-center justify-center hover:bg-info-100" title="Detail">
                                        <iconify-icon icon="solar:eye-bold" class="text-lg"></iconify-icon>
                                    </a>
                                    <a href="{{ route('guru-bk.homeroom-consultations.edit', $consultation) }}" class="w-8 h-8 bg-primary-50 dark:bg-primary-600/25 text-primary-600 rounded-lg flex items-center justify-center hover:bg-primary-100" title="Edit">
                                        <iconify-icon icon="solar:pen-bold" class="text-lg"></iconify-icon>
                                    </a>
                                    <form action="{{ route('guru-bk.homeroom-consultations.destroy', $consultation) }}" method="POST" onsubmit="return confirm('Hapus data konsultasi ini?')">
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
                                    <p class="mb-0">Belum ada data konsultasi wali kelas.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($consultations->hasPages())
            <div class="mt-4">
                {{ $consultations->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
