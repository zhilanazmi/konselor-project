@extends('layouts.app')

@section('title', 'Edit Siswa - KonselorKita')

@section('content')
<div class="card">
    <div class="card-header">
        <h6 class="text-lg font-semibold mb-0">Edit Siswa: {{ $student->full_name }}</h6>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.students.update', $student) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label for="nis" class="form-label">NIS <span class="text-danger-600">*</span></label>
                    <input type="text" id="nis" name="nis" value="{{ old('nis', $student->nis) }}" class="form-control @error('nis') !border-danger-600 @enderror">
                    @error('nis')
                        <p class="text-danger-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="full_name" class="form-label">Nama Lengkap <span class="text-danger-600">*</span></label>
                    <input type="text" id="full_name" name="full_name" value="{{ old('full_name', $student->full_name) }}" class="form-control @error('full_name') !border-danger-600 @enderror">
                    @error('full_name')
                        <p class="text-danger-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                <div>
                    <label for="gender" class="form-label">Jenis Kelamin <span class="text-danger-600">*</span></label>
                    <select id="gender" name="gender" class="form-control @error('gender') !border-danger-600 @enderror">
                        <option value="">-- Pilih --</option>
                        <option value="L" {{ old('gender', $student->gender) === 'L' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="P" {{ old('gender', $student->gender) === 'P' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                    @error('gender')
                        <p class="text-danger-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="birth_place" class="form-label">Tempat Lahir</label>
                    <input type="text" id="birth_place" name="birth_place" value="{{ old('birth_place', $student->birth_place) }}" class="form-control">
                </div>
                <div>
                    <label for="birth_date" class="form-label">Tanggal Lahir</label>
                    <input type="date" id="birth_date" name="birth_date" value="{{ old('birth_date', $student->birth_date?->format('Y-m-d')) }}" class="form-control">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label for="phone" class="form-label">Telepon</label>
                    <input type="text" id="phone" name="phone" value="{{ old('phone', $student->phone) }}" class="form-control">
                </div>
                <div>
                    <label for="classroom_id" class="form-label">Kelas (Tahun Ajaran Aktif)</label>
                    @php
                        $currentClassroomId = old('classroom_id', $student->classrooms->first(fn ($c) => $c->academicYear?->is_active)?->id);
                    @endphp
                    <select id="classroom_id" name="classroom_id" class="form-control dark:text-white dark:bg-neutral-700">
                        <option value="" class="dark:bg-neutral-700 dark:text-white">-- Tidak Ada Kelas --</option>
                        @foreach($classrooms as $classroom)
                            <option value="{{ $classroom->id }}" class="dark:bg-neutral-700 dark:text-white" {{ $currentClassroomId == $classroom->id ? 'selected' : '' }}>
                                {{ $classroom->name }} ({{ $classroom->academicYear->name }})
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="mb-6">
                <label for="address" class="form-label">Alamat</label>
                <textarea id="address" name="address" rows="3" class="form-control">{{ old('address', $student->address) }}</textarea>
            </div>

            <div class="flex items-center gap-3">
                <button type="submit" class="btn btn-primary-600 flex items-center gap-2">
                    <iconify-icon icon="solar:diskette-bold" class="text-xl"></iconify-icon>
                    Perbarui
                </button>
                <a href="{{ route('admin.students.index') }}" class="btn btn-outline-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
