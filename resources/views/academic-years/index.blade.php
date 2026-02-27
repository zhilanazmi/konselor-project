@extends('layouts.app')

@section('title', 'Tahun Ajaran - KonselorKita')

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
        <div class="flex items-center gap-4">
            <h6 class="text-lg font-semibold mb-0">Daftar Tahun Ajaran</h6>
        </div>
        <div class="flex flex-wrap items-center gap-3">
            {{-- Search --}}
            <form action="{{ route('admin.academic-years.index') }}" method="GET" class="flex items-center gap-2">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari tahun ajaran..." class="form-control !rounded-lg">
                <button type="submit" class="btn btn-primary-600 !rounded-lg flex items-center gap-2">
                    <iconify-icon icon="ion:search-outline" class="text-xl"></iconify-icon>
                    Cari
                </button>
                @if(request('search'))
                    <a href="{{ route('admin.academic-years.index') }}" class="btn btn-outline-secondary !rounded-lg">Reset</a>
                @endif
            </form>
            {{-- Add Button --}}
            <a href="{{ route('admin.academic-years.create') }}" class="btn btn-primary-600 flex items-center gap-2 !rounded-lg">
                <iconify-icon icon="ic:baseline-plus" class="text-xl"></iconify-icon>
                Tambah Tahun Ajaran
            </a>
        </div>
    </div>
    <div class="card-body">
        <div class="overflow-x-auto">
            <table class="table bordered-table mb-0">
                <thead>
                    <tr>
                        <th scope="col" class="!text-center w-12">No</th>
                        <th scope="col">Nama</th>
                        <th scope="col">Tanggal Mulai</th>
                        <th scope="col">Tanggal Selesai</th>
                        <th scope="col" class="!text-center">Status</th>
                        <th scope="col" class="!text-center w-40">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($academicYears as $index => $year)
                        <tr>
                            <td class="!text-center">{{ $academicYears->firstItem() + $index }}</td>
                            <td>
                                <span class="font-medium">{{ $year->name }}</span>
                            </td>
                            <td>{{ $year->start_date->format('d M Y') }}</td>
                            <td>{{ $year->end_date->format('d M Y') }}</td>
                            <td class="!text-center">
                                @if($year->is_active)
                                    <span class="bg-success-100 dark:bg-success-600/25 text-success-600 dark:text-success-400 px-3 py-1 rounded-full text-xs font-medium">Aktif</span>
                                @else
                                    <span class="bg-neutral-200 dark:bg-neutral-600 text-neutral-600 dark:text-neutral-300 px-3 py-1 rounded-full text-xs font-medium">Tidak Aktif</span>
                                @endif
                            </td>
                            <td class="!text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('admin.academic-years.edit', $year) }}" class="w-8 h-8 bg-primary-50 dark:bg-primary-600/25 text-primary-600 rounded-lg flex items-center justify-center hover:bg-primary-100" title="Edit">
                                        <iconify-icon icon="solar:pen-bold" class="text-lg"></iconify-icon>
                                    </a>
                                    <form action="{{ route('admin.academic-years.destroy', $year) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus tahun ajaran ini?')">
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
                            <td colspan="6" class="!text-center py-8">
                                <div class="flex flex-col items-center gap-2 text-neutral-400">
                                    <iconify-icon icon="solar:folder-open-bold" class="text-4xl"></iconify-icon>
                                    <p class="mb-0">Belum ada data tahun ajaran.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($academicYears->hasPages())
            <div class="mt-4">
                {{ $academicYears->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
