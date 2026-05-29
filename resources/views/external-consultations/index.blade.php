@extends('layouts.app')

@section('title', 'Konsultasi Pihak Luar - KonselorKita')

@section('content')

@if(session('success'))
    <div class="bg-success-100 dark:bg-success-600/25 text-success-600 dark:text-success-400 px-4 py-3 rounded-lg mb-4 flex items-center gap-2">
        <iconify-icon icon="solar:check-circle-bold" class="text-xl"></iconify-icon>{{ session('success') }}
    </div>
@endif

<div class="card">
    <div class="card-header flex flex-wrap items-center justify-between gap-3">
        <h6 class="text-lg font-semibold mb-0">Konsultasi Pihak Luar</h6>
        <a href="{{ route('guru-bk.external-consultations.create') }}" class="btn btn-primary-600 flex items-center gap-2">
            <iconify-icon icon="ic:baseline-plus" class="text-xl"></iconify-icon>Tambah
        </a>
    </div>
    <div class="card-body">
        {{-- Filter --}}
        <form method="GET" class="flex flex-wrap gap-3 mb-4">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama pihak luar / siswa..." class="form-control !w-auto flex-1 min-w-48">
            <select name="academic_year_id" class="form-control !w-auto">
                <option value="">Semua Tahun Ajaran</option>
                @foreach($academicYears as $year)
                    <option value="{{ $year->id }}" {{ request('academic_year_id') == $year->id ? 'selected' : '' }}>{{ $year->name }}</option>
                @endforeach
            </select>
            <button type="submit" class="btn btn-primary-600">Filter</button>
            <a href="{{ route('guru-bk.external-consultations.index') }}" class="btn btn-outline-secondary">Reset</a>
        </form>

        <div class="overflow-x-auto">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Nama Pihak Luar</th>
                        <th>Peran/Hubungan</th>
                        <th>Siswa Terkait</th>
                        <th>Topik</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($consultations as $consultation)
                        <tr>
                            <td class="text-sm">{{ $consultation->consultation_date->format('d M Y') }}</td>
                            <td class="font-medium">{{ $consultation->external_party_name }}</td>
                            <td><span class="px-2 py-0.5 rounded-full text-xs bg-blue-100 text-blue-600">{{ $consultation->external_party_role }}</span></td>
                            <td class="text-sm">{{ $consultation->student?->full_name ?? '-' }}</td>
                            <td class="text-sm max-w-xs truncate">{{ $consultation->topic }}</td>
                            <td>
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('guru-bk.external-consultations.show', $consultation) }}" class="w-8 h-8 bg-primary-50 dark:bg-primary-600/25 text-primary-600 rounded-lg flex items-center justify-center hover:bg-primary-100" title="Detail">
                                        <iconify-icon icon="solar:eye-bold" class="text-sm"></iconify-icon>
                                    </a>
                                    <a href="{{ route('guru-bk.external-consultations.edit', $consultation) }}" class="w-8 h-8 bg-warning-50 dark:bg-warning-600/25 text-warning-600 rounded-lg flex items-center justify-center hover:bg-warning-100" title="Edit">
                                        <iconify-icon icon="solar:pen-bold" class="text-sm"></iconify-icon>
                                    </a>
                                    <form action="{{ route('guru-bk.external-consultations.destroy', $consultation) }}" method="POST" onsubmit="return confirm('Hapus data ini?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="w-8 h-8 bg-danger-50 dark:bg-danger-600/25 text-danger-600 rounded-lg flex items-center justify-center hover:bg-danger-100" title="Hapus">
                                            <iconify-icon icon="solar:trash-bin-trash-bold" class="text-sm"></iconify-icon>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center text-neutral-400 py-8">Belum ada data konsultasi pihak luar.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4">{{ $consultations->links() }}</div>
    </div>
</div>
@endsection
