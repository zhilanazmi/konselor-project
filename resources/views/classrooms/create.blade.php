@extends('layouts.app')

@section('title', 'Tambah Kelas - KonselorKita')

@section('content')
<div class="card">
    <div class="card-header">
        <h6 class="text-lg font-semibold mb-0">Form Tambah Kelas</h6>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.classrooms.store') }}" method="POST">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label for="academic_year_id" class="form-label">Tahun Ajaran <span class="text-danger-600">*</span></label>
                    <select id="academic_year_id" name="academic_year_id" class="form-control @error('academic_year_id') !border-danger-600 @enderror">
                        <option value="">-- Pilih Tahun Ajaran --</option>
                        @foreach($academicYears as $year)
                            <option value="{{ $year->id }}" {{ old('academic_year_id') == $year->id ? 'selected' : '' }}>
                                {{ $year->name }} {{ $year->is_active ? '(Aktif)' : '' }}
                            </option>
                        @endforeach
                    </select>
                    @error('academic_year_id')
                        <p class="text-danger-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="homeroom_teacher_id" class="form-label">Wali Kelas <span class="text-danger-600">*</span></label>
                    <select id="homeroom_teacher_id" name="homeroom_teacher_id" class="form-control @error('homeroom_teacher_id') !border-danger-600 @enderror">
                        <option value="">-- Pilih Wali Kelas --</option>
                        @foreach($teachers as $teacher)
                            <option value="{{ $teacher->id }}" {{ old('homeroom_teacher_id') == $teacher->id ? 'selected' : '' }}>
                                {{ $teacher->full_name }} {{ $teacher->subject ? '(' . $teacher->subject . ')' : '' }}
                            </option>
                        @endforeach
                    </select>
                    @error('homeroom_teacher_id')
                        <p class="text-danger-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                <div>
                    <label for="name" class="form-label">Nama Kelas <span class="text-danger-600">*</span></label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}" class="form-control @error('name') !border-danger-600 @enderror" placeholder="Contoh: VII-A">
                    @error('name')
                        <p class="text-danger-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="grade" class="form-label">Tingkat <span class="text-danger-600">*</span></label>
                    <select id="grade" name="grade" class="form-control @error('grade') !border-danger-600 @enderror">
                        <option value="">-- Pilih Tingkat --</option>
                        <option value="7" {{ old('grade') === '7' ? 'selected' : '' }}>Kelas 7</option>
                        <option value="8" {{ old('grade') === '8' ? 'selected' : '' }}>Kelas 8</option>
                        <option value="9" {{ old('grade') === '9' ? 'selected' : '' }}>Kelas 9</option>
                    </select>
                    @error('grade')
                        <p class="text-danger-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="flex items-center gap-3">
                <button type="submit" class="btn btn-primary-600 flex items-center gap-2">
                    <iconify-icon icon="solar:diskette-bold" class="text-xl"></iconify-icon>
                    Simpan
                </button>
                <a href="{{ route('admin.classrooms.index') }}" class="btn btn-outline-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
