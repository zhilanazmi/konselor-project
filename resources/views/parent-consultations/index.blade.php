@extends('layouts.app')

@section('title', 'Konsultasi Orang Tua - KonselorKita')

@section('content')

@if(session('success'))
    <div class="bg-success-100 dark:bg-success-600/25 text-success-600 dark:text-success-400 px-4 py-3 rounded-lg mb-4 flex items-center gap-2">
        <iconify-icon icon="solar:check-circle-bold" class="text-xl"></iconify-icon>
        {{ session('success') }}
    </div>
@endif

<div class="card">
    <div class="card-header flex flex-wrap items-center justify-between gap-4">
        <h6 class="text-lg font-semibold mb-0">Daftar Konsultasi Orang Tua</h6>
        <a href="{{ route('guru-bk.parent-consultations.create') }}" class="btn btn-primary-600 flex items-center gap-2 !rounded-lg">
            <iconify-icon icon="ic:baseline-plus" class="text-xl"></iconify-icon>
            Tambah Konsultasi
        </a>
    </div>

    {{-- Filter Bar --}}
    <div class="card-body border-b border-neutral-200 dark:border-neutral-700 pb-4">
        <form action="{{ route('guru-bk.parent-consultations.index') }}" method="GET" class="flex flex-wrap items-end gap-3">
            <div class="flex-1 min-w-[200px]">
                <label class="form-label text-xs">Cari Siswa / Nama Orang Tua</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Nama siswa atau orang tua..." class="form-control !rounded-lg">
            </div>
            <div class="min-w-[130px]">
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
            <div class="min-w-[120px]">
                <label class="form-label text-xs">Pemohon</label>
                <select name="requested_by" class="form-control !rounded-lg">
                    <option value="">Semua</option>
                    <option value="guru_bk" {{ request('requested_by') == 'guru_bk' ? 'selected' : '' }}>Guru BK</option>
                    <option value="orang_tua" {{ request('requested_by') == 'orang_tua' ? 'selected' : '' }}>Orang Tua</option>
                </select>
            </div>
            <div class="min-w-[120px]">
                <label class="form-label text-xs">Status</label>
                <select name="status" class="form-control !rounded-lg">
                    <option value="">Semua Status</option>
                    <option value="requested" {{ request('status') == 'requested' ? 'selected' : '' }}>Diminta</option>
                    <option value="scheduled" {{ request('status') == 'scheduled' ? 'selected' : '' }}>Dijadwalkan</option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Selesai</option>
                </select>
            </div>
            <div class="flex gap-2">
                <button type="submit" class="btn btn-primary-600 !rounded-lg flex items-center gap-2">
                    <iconify-icon icon="ion:search-outline" class="text-xl"></iconify-icon>
                    Filter
                </button>
                @if(request()->hasAny(['search', 'academic_year_id', 'requested_by', 'status']))
                    <a href="{{ route('guru-bk.parent-consultations.index') }}" class="btn btn-outline-secondary !rounded-lg">Reset</a>
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
                        <th scope="col">Orang Tua / Wali</th>
                        <th scope="col">Waktu Jadwal</th>
                        <th scope="col">Pemohon</th>
                        <th scope="col">Status</th>
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
                            <td>{{ $consultation->guardian->full_name }}</td>
                            <td class="text-sm font-medium">
                                {{ $consultation->scheduled_at->format('d M Y, H:i') }}
                            </td>
                            <td>
                                @if($consultation->requested_by == 'guru_bk')
                                    <span class="px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-600 dark:bg-blue-900/30 dark:text-blue-400">Guru BK</span>
                                @else
                                    <span class="px-2 py-0.5 rounded text-xs font-medium bg-purple-100 text-purple-600 dark:bg-purple-900/30 dark:text-purple-400">Orang Tua</span>
                                @endif
                            </td>
                            <td>
                                @if($consultation->status == 'requested')
                                    <span class="px-2 py-0.5 rounded text-xs font-medium bg-warning-100 text-warning-600 dark:bg-warning-900/30 dark:text-warning-400 border border-warning-200 dark:border-warning-800">Diminta</span>
                                @elseif($consultation->status == 'scheduled')
                                    <span class="px-2 py-0.5 rounded text-xs font-medium bg-info-100 text-info-600 dark:bg-info-900/30 dark:text-info-400 border border-info-200 dark:border-info-800">Dijadwalkan</span>
                                @else
                                    <span class="px-2 py-0.5 rounded text-xs font-medium bg-success-100 text-success-600 dark:bg-success-900/30 dark:text-success-400 border border-success-200 dark:border-success-800">Selesai</span>
                                @endif
                            </td>
                            <td class="!text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('guru-bk.parent-consultations.show', $consultation) }}" class="w-8 h-8 bg-info-50 dark:bg-info-600/25 text-info-600 rounded-lg flex items-center justify-center hover:bg-info-100" title="Detail">
                                        <iconify-icon icon="solar:eye-bold" class="text-lg"></iconify-icon>
                                    </a>
                                    <a href="{{ route('guru-bk.parent-consultations.edit', $consultation) }}" class="w-8 h-8 bg-primary-50 dark:bg-primary-600/25 text-primary-600 rounded-lg flex items-center justify-center hover:bg-primary-100" title="Edit">
                                        <iconify-icon icon="solar:pen-bold" class="text-lg"></iconify-icon>
                                    </a>
                                    <form action="{{ route('guru-bk.parent-consultations.destroy', $consultation) }}" method="POST" onsubmit="return confirm('Hapus data konsultasi ini?')">
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
                                    <p class="mb-0">Belum ada data konsultasi orang tua.</p>
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
