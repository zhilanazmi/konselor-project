@extends('layouts.app')

@section('title', 'Data Kelas - KonselorKita')

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
        <h6 class="text-lg font-semibold mb-0">Daftar Kelas</h6>
        <div class="flex flex-wrap items-center gap-3">
            <form action="{{ route('admin.classrooms.index') }}" method="GET" class="flex items-center gap-2">
                <select name="academic_year_id" class="form-control !rounded-lg" onchange="this.form.submit()">
                    <option value="">Semua Tahun Ajaran</option>
                    @foreach($academicYears as $year)
                        <option value="{{ $year->id }}" {{ request('academic_year_id') == $year->id ? 'selected' : '' }}>
                            {{ $year->name }} {{ $year->is_active ? '(Aktif)' : '' }}
                        </option>
                    @endforeach
                </select>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari kelas..." class="form-control !rounded-lg">
                <button type="submit" class="btn btn-primary-600 !rounded-lg flex items-center gap-2">
                    <iconify-icon icon="ion:search-outline" class="text-xl"></iconify-icon>
                    Cari
                </button>
                @if(request('search') || request('academic_year_id'))
                    <a href="{{ route('admin.classrooms.index') }}" class="btn btn-outline-secondary !rounded-lg">Reset</a>
                @endif
            </form>
            <a href="{{ route('admin.classrooms.create') }}" class="btn btn-primary-600 flex items-center gap-2 !rounded-lg">
                <iconify-icon icon="ic:baseline-plus" class="text-xl"></iconify-icon>
                Tambah Kelas
            </a>
        </div>
    </div>
    <div class="card-body">
        <div class="overflow-x-auto">
            <table class="table bordered-table mb-0">
                <thead>
                    <tr>
                        <th scope="col" class="!text-center w-12">No</th>
                        <th scope="col">Nama Kelas</th>
                        <th scope="col" class="!text-center">Tingkat</th>
                        <th scope="col">Tahun Ajaran</th>
                        <th scope="col">Wali Kelas</th>
                        <th scope="col" class="!text-center">Jml Siswa</th>
                        <th scope="col" class="!text-center w-48">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($classrooms as $index => $classroom)
                        <tr>
                            <td class="!text-center">{{ $classrooms->firstItem() + $index }}</td>
                            <td><span class="font-medium">{{ $classroom->name }}</span></td>
                            <td class="!text-center">
                                <span class="px-2 py-0.5 rounded text-xs font-medium bg-primary-100 text-primary-600 dark:bg-primary-900/30 dark:text-primary-400">
                                    Kelas {{ $classroom->grade }}
                                </span>
                            </td>
                            <td>{{ $classroom->academicYear->name }}</td>
                            <td>{{ $classroom->homeroomTeacher->full_name }}</td>
                            <td class="!text-center">
                                <span class="font-semibold">{{ $classroom->students_count }}</span>
                            </td>
                            <td class="!text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('admin.classrooms.show', $classroom) }}" class="w-8 h-8 bg-success-50 dark:bg-success-600/25 text-success-600 rounded-lg flex items-center justify-center hover:bg-success-100" title="Detail & Siswa">
                                        <iconify-icon icon="solar:eye-bold" class="text-lg"></iconify-icon>
                                    </a>
                                    <a href="{{ route('admin.classrooms.edit', $classroom) }}" class="w-8 h-8 bg-primary-50 dark:bg-primary-600/25 text-primary-600 rounded-lg flex items-center justify-center hover:bg-primary-100" title="Edit">
                                        <iconify-icon icon="solar:pen-bold" class="text-lg"></iconify-icon>
                                    </a>
                                    <form action="{{ route('admin.classrooms.destroy', $classroom) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kelas ini?')">
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
                                    <p class="mb-0">Belum ada data kelas.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($classrooms->hasPages())
            <div class="mt-4">
                {{ $classrooms->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
