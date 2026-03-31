@extends('layouts.app')

@section('title', 'Edit Konsultasi Guru Mapel - KonselorKita')

@section('content')
<div class="card">
    <div class="card-header">
        <h6 class="text-lg font-semibold mb-0">Edit Konsultasi Guru Mata Pelajaran</h6>
    </div>
    <div class="card-body">
        <form action="{{ route('guru-bk.subject-teacher-consultations.update', $consultation) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label for="teacher_id" class="form-label">Guru Mata Pelajaran <span class="text-danger-600">*</span></label>
                    <select id="teacher_id" name="teacher_id" class="form-control @error('teacher_id') !border-danger-600 @enderror">
                        <option value="">-- Pilih Guru --</option>
                        @foreach($teachers as $teacher)
                            <option value="{{ $teacher->id }}"
                                data-subject="{{ $teacher->subject }}"
                                {{ old('teacher_id', $consultation->teacher_id) == $teacher->id ? 'selected' : '' }}>
                                {{ $teacher->full_name }}
                                @if($teacher->subject) — {{ $teacher->subject }} @endif
                            </option>
                        @endforeach
                    </select>
                    @error('teacher_id')
                        <p class="text-danger-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="subject_name" class="form-label">Mata Pelajaran <span class="text-danger-600">*</span></label>
                    <input type="text" id="subject_name" name="subject_name"
                        value="{{ old('subject_name', $consultation->subject_name) }}"
                        class="form-control @error('subject_name') !border-danger-600 @enderror">
                    @error('subject_name')
                        <p class="text-danger-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label for="student_id" class="form-label">Siswa yang Dibahas <span class="text-danger-600">*</span></label>
                    <select id="student_id" name="student_id" class="form-control @error('student_id') !border-danger-600 @enderror">
                        <option value="">-- Pilih Siswa --</option>
                        @foreach($students as $student)
                            <option value="{{ $student->id }}" {{ old('student_id', $consultation->student_id) == $student->id ? 'selected' : '' }}>
                                {{ $student->full_name }} ({{ $student->nis }})
                            </option>
                        @endforeach
                    </select>
                    @error('student_id')
                        <p class="text-danger-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="academic_year_id" class="form-label">Tahun Ajaran <span class="text-danger-600">*</span></label>
                    <select id="academic_year_id" name="academic_year_id" class="form-control @error('academic_year_id') !border-danger-600 @enderror">
                        <option value="">-- Pilih Tahun Ajaran --</option>
                        @foreach($academicYears as $year)
                            <option value="{{ $year->id }}" {{ old('academic_year_id', $consultation->academic_year_id) == $year->id ? 'selected' : '' }}>
                                {{ $year->name }}{{ $year->is_active ? ' (Aktif)' : '' }}
                            </option>
                        @endforeach
                    </select>
                    @error('academic_year_id')
                        <p class="text-danger-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="mb-4">
                <label for="consultation_date" class="form-label">Tanggal Konsultasi <span class="text-danger-600">*</span></label>
                <input type="datetime-local" id="consultation_date" name="consultation_date"
                    value="{{ old('consultation_date', $consultation->consultation_date?->format('Y-m-d\TH:i')) }}"
                    class="form-control @error('consultation_date') !border-danger-600 @enderror">
                @error('consultation_date')
                    <p class="text-danger-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="topic" class="form-label">Topik / Permasalahan <span class="text-danger-600">*</span></label>
                <textarea id="topic" name="topic" rows="4" class="form-control @error('topic') !border-danger-600 @enderror">{{ old('topic', $consultation->topic) }}</textarea>
                @error('topic')
                    <p class="text-danger-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="recommendation" class="form-label">Rekomendasi</label>
                <textarea id="recommendation" name="recommendation" rows="3" class="form-control">{{ old('recommendation', $consultation->recommendation) }}</textarea>
            </div>

            <div class="mb-6">
                <label for="follow_up" class="form-label">Tindak Lanjut</label>
                <textarea id="follow_up" name="follow_up" rows="3" class="form-control">{{ old('follow_up', $consultation->follow_up) }}</textarea>
            </div>

            <div class="flex items-center gap-3">
                <button type="submit" class="btn btn-primary-600 flex items-center gap-2">
                    <iconify-icon icon="solar:diskette-bold" class="text-xl"></iconify-icon>
                    Perbarui
                </button>
                <a href="{{ route('guru-bk.subject-teacher-consultations.show', $consultation) }}" class="btn btn-outline-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
