@extends('layouts.app')

@section('title', 'Detail Kelas - KonselorKita')

@section('content')

@if(session('success'))
    <div class="bg-success-100 dark:bg-success-600/25 text-success-600 dark:text-success-400 px-4 py-3 rounded-lg mb-4 flex items-center gap-2">
        <iconify-icon icon="solar:check-circle-bold" class="text-xl"></iconify-icon>
        {{ session('success') }}
    </div>
@endif

{{-- Classroom Info --}}
<div class="card mb-6">
    <div class="card-body">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <div>
                <p class="text-neutral-400 text-sm mb-1">Nama Kelas</p>
                <h6 class="font-semibold">{{ $classroom->name }}</h6>
            </div>
            <div>
                <p class="text-neutral-400 text-sm mb-1">Tingkat</p>
                <h6 class="font-semibold">Kelas {{ $classroom->grade }}</h6>
            </div>
            <div>
                <p class="text-neutral-400 text-sm mb-1">Tahun Ajaran</p>
                <h6 class="font-semibold">{{ $classroom->academicYear->name }}</h6>
            </div>
            <div>
                <p class="text-neutral-400 text-sm mb-1">Wali Kelas</p>
                <h6 class="font-semibold">{{ $classroom->homeroomTeacher->full_name }}</h6>
            </div>
        </div>
    </div>
</div>

{{-- Add Students --}}
<div class="card mb-6">
    <div class="card-header">
        <h6 class="text-lg font-semibold mb-0">Tambah Siswa ke Kelas</h6>
    </div>
    <div class="card-body">
        @if($availableStudents->isEmpty())
            <p class="text-neutral-400 mb-0">Semua siswa sudah terdaftar di kelas ini.</p>
        @else
            <form action="{{ route('admin.classrooms.add-students', $classroom) }}" method="POST">
                @csrf
                <div class="flex flex-wrap items-end gap-3">
                    <div class="flex-1 min-w-[300px]">
                        <label for="student_ids" class="form-label">Pilih Siswa</label>
                        <select id="student_ids" name="student_ids[]" multiple class="form-control" size="5">
                            @foreach($availableStudents as $student)
                                <option value="{{ $student->id }}">{{ $student->nis }} — {{ $student->full_name }}</option>
                            @endforeach
                        </select>
                        <p class="text-neutral-400 text-xs mt-1">Tahan Ctrl/Cmd untuk memilih beberapa siswa.</p>
                        @error('student_ids')
                            <p class="text-danger-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-primary-600 flex items-center gap-2 h-fit">
                        <iconify-icon icon="ic:baseline-plus" class="text-xl"></iconify-icon>
                        Tambahkan
                    </button>
                </div>
            </form>
        @endif
    </div>
</div>

{{-- Student List --}}
<div class="card">
    <div class="card-header flex items-center justify-between">
        <h6 class="text-lg font-semibold mb-0">Daftar Siswa ({{ $classroom->students->count() }})</h6>
    </div>
    <div class="card-body">
        <div class="overflow-x-auto">
            <table class="table bordered-table mb-0">
                <thead>
                    <tr>
                        <th scope="col" class="!text-center w-12">No</th>
                        <th scope="col">NIS</th>
                        <th scope="col">Nama Lengkap</th>
                        <th scope="col" class="!text-center">L/P</th>
                        <th scope="col" class="!text-center w-24">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($classroom->students as $index => $student)
                        <tr>
                            <td class="!text-center">{{ $index + 1 }}</td>
                            <td><span class="font-mono">{{ $student->nis }}</span></td>
                            <td><span class="font-medium">{{ $student->full_name }}</span></td>
                            <td class="!text-center">
                                <span class="px-2 py-0.5 rounded text-xs font-medium {{ $student->gender === 'L' ? 'bg-blue-100 text-blue-600 dark:bg-blue-900/30 dark:text-blue-400' : 'bg-pink-100 text-pink-600 dark:bg-pink-900/30 dark:text-pink-400' }}">
                                    {{ $student->gender }}
                                </span>
                            </td>
                            <td class="!text-center">
                                <form action="{{ route('admin.classrooms.remove-student', [$classroom, $student]) }}" method="POST" onsubmit="return confirm('Hapus siswa ini dari kelas?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="w-8 h-8 bg-danger-50 dark:bg-danger-600/25 text-danger-600 rounded-lg flex items-center justify-center hover:bg-danger-100 mx-auto" title="Hapus dari kelas">
                                        <iconify-icon icon="solar:minus-circle-bold" class="text-lg"></iconify-icon>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="!text-center py-8">
                                <div class="flex flex-col items-center gap-2 text-neutral-400">
                                    <iconify-icon icon="solar:folder-open-bold" class="text-4xl"></iconify-icon>
                                    <p class="mb-0">Belum ada siswa di kelas ini.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="mt-4">
    <a href="{{ route('admin.classrooms.index') }}" class="btn btn-outline-secondary flex items-center gap-2 w-fit">
        <iconify-icon icon="solar:arrow-left-bold" class="text-xl"></iconify-icon>
        Kembali ke Daftar Kelas
    </a>
</div>
@endsection
