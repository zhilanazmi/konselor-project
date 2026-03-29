@extends('layouts.app')

@section('title', 'Tambah Konsultasi Wali Kelas - KonselorKita')

@section('content')
<div class="card">
    <div class="card-header">
        <h6 class="text-lg font-semibold mb-0">Form Tambah Konsultasi Wali Kelas</h6>
    </div>
    <div class="card-body">
        <form action="{{ route('guru-bk.homeroom-consultations.store') }}" method="POST">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label for="teacher_id" class="form-label">Wali Kelas <span class="text-danger-600">*</span></label>
                    <select id="teacher_id" name="teacher_id" class="form-control @error('teacher_id') !border-danger-600 @enderror">
                        <option value="">-- Pilih Wali Kelas --</option>
                        @foreach($teachers as $teacher)
                            <option value="{{ $teacher->id }}" {{ old('teacher_id') == $teacher->id ? 'selected' : '' }}>
                                {{ $teacher->full_name }}
                                @if($teacher->homeroomClassrooms->isNotEmpty())
                                    ({{ $teacher->homeroomClassrooms->pluck('name')->join(', ') }})
                                @endif
                            </option>
                        @endforeach
                    </select>
                    @error('teacher_id')
                        <p class="text-danger-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="student_id" class="form-label">Siswa yang Dibahas <span class="text-danger-600">*</span></label>
                    <select id="student_id" name="student_id" class="form-control @error('student_id') !border-danger-600 @enderror">
                        <option value="">-- Pilih Siswa --</option>
                        @foreach($students as $student)
                            <option value="{{ $student->id }}" {{ old('student_id') == $student->id ? 'selected' : '' }}>
                                {{ $student->full_name }} ({{ $student->nis }})
                            </option>
                        @endforeach
                    </select>
                    @error('student_id')
                        <p class="text-danger-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label for="academic_year_id" class="form-label">Tahun Ajaran <span class="text-danger-600">*</span></label>
                    <select id="academic_year_id" name="academic_year_id" class="form-control @error('academic_year_id') !border-danger-600 @enderror">
                        <option value="">-- Pilih Tahun Ajaran --</option>
                        @foreach($academicYears as $year)
                            <option value="{{ $year->id }}" {{ (old('academic_year_id', $activeAcademicYear?->id)) == $year->id ? 'selected' : '' }}>
                                {{ $year->name }}{{ $year->is_active ? ' (Aktif)' : '' }}
                            </option>
                        @endforeach
                    </select>
                    @error('academic_year_id')
                        <p class="text-danger-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="consultation_date" class="form-label">Tanggal Konsultasi <span class="text-danger-600">*</span></label>
                    <input type="datetime-local" id="consultation_date" name="consultation_date" value="{{ old('consultation_date') }}" class="form-control @error('consultation_date') !border-danger-600 @enderror">
                    @error('consultation_date')
                        <p class="text-danger-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="mb-4">
                <label for="topic" class="form-label">Topik Konsultasi <span class="text-danger-600">*</span></label>
                <textarea id="topic" name="topic" rows="3" class="form-control @error('topic') !border-danger-600 @enderror" placeholder="Uraikan topik yang dibicarakan dalam konsultasi ini...">{{ old('topic') }}</textarea>
                @error('topic')
                    <p class="text-danger-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="recommendation" class="form-label">Rekomendasi</label>
                <textarea id="recommendation" name="recommendation" rows="3" class="form-control" placeholder="Rekomendasi yang diberikan kepada wali kelas...">{{ old('recommendation') }}</textarea>
            </div>

            <div class="mb-6">
                <label for="follow_up" class="form-label">Tindak Lanjut</label>
                <textarea id="follow_up" name="follow_up" rows="3" class="form-control" placeholder="Rencana tindak lanjut dari hasil konsultasi...">{{ old('follow_up') }}</textarea>
            </div>

            <div class="flex items-center gap-3">
                <button type="submit" class="btn btn-primary-600 flex items-center gap-2">
                    <iconify-icon icon="solar:diskette-bold" class="text-xl"></iconify-icon>
                    Simpan
                </button>
                <a href="{{ route('guru-bk.homeroom-consultations.index') }}" class="btn btn-outline-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Eager load classroom info for teacher — dropdown already shows it
</script>
@endpush
