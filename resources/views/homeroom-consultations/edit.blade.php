@extends('layouts.app')

@section('title', 'Edit Konsultasi Wali Kelas - KonselorKita')

@section('content')
<div class="card">
    <div class="card-header">
        <h6 class="text-lg font-semibold mb-0">Edit Konsultasi Wali Kelas</h6>
    </div>
    <div class="card-body">
        <form action="{{ route('guru-bk.homeroom-consultations.update', $consultation) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label for="teacher_id" class="form-label">Wali Kelas <span class="text-danger-600">*</span></label>
                    <select id="teacher_id" name="teacher_id" class="form-control @error('teacher_id') !border-danger-600 @enderror">
                        <option value="">-- Pilih Wali Kelas --</option>
                        @foreach($teachers as $teacher)
                            <option value="{{ $teacher->id }}" {{ old('teacher_id', $consultation->teacher_id) == $teacher->id ? 'selected' : '' }}>
                                {{ $teacher->full_name }}
                                @if($teacher->homeroomClassrooms->isNotEmpty())
                                    ({{ $teacher->homeroomClassrooms->pluck('name')->join(', ') }})
                                @endif
                            </option>
                        @endforeach
                    </select>
                    @error('teacher_id')<p class="text-danger-600 text-sm mt-1">{{ $message }}</p>@enderror
                </div>
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
                    @error('student_id')<p class="text-danger-600 text-sm mt-1">{{ $message }}</p>@enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
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
                    @error('academic_year_id')<p class="text-danger-600 text-sm mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="consultation_date" class="form-label">Tanggal Konsultasi <span class="text-danger-600">*</span></label>
                    <input type="datetime-local" id="consultation_date" name="consultation_date"
                        value="{{ old('consultation_date', $consultation->consultation_date?->format('Y-m-d\TH:i')) }}"
                        class="form-control @error('consultation_date') !border-danger-600 @enderror">
                    @error('consultation_date')<p class="text-danger-600 text-sm mt-1">{{ $message }}</p>@enderror
                </div>
            </div>

            <div class="mb-4">
                <label for="topic" class="form-label">Topik Konsultasi <span class="text-danger-600">*</span></label>
                <textarea id="topic" name="topic" rows="3" class="form-control @error('topic') !border-danger-600 @enderror">{{ old('topic', $consultation->topic) }}</textarea>
                @error('topic')<p class="text-danger-600 text-sm mt-1">{{ $message }}</p>@enderror
            </div>

            <div class="mb-4">
                <label for="recommendation" class="form-label">Rekomendasi</label>
                <textarea id="recommendation" name="recommendation" rows="3" class="form-control">{{ old('recommendation', $consultation->recommendation) }}</textarea>
            </div>

            <div class="mb-4">
                <label for="evaluation" class="form-label">Evaluasi</label>
                <textarea id="evaluation" name="evaluation" rows="3" class="form-control">{{ old('evaluation', $consultation->evaluation) }}</textarea>
            </div>

            <div class="mb-4">
                <label for="follow_up" class="form-label">Tindak Lanjut</label>
                <textarea id="follow_up" name="follow_up" rows="3" class="form-control">{{ old('follow_up', $consultation->follow_up) }}</textarea>
            </div>

            {{-- Upload Foto --}}
            <div class="mb-4">
                <label class="form-label">Tambah Foto Dokumentasi (JPG/PNG, maks. 5 MB per file)</label>
                <input type="file" name="documents[]" multiple accept=".jpg,.jpeg,.png"
                    class="form-control @error('documents.*') !border-danger-600 @enderror">
                @error('documents.*')<p class="text-danger-600 text-sm mt-1">{{ $message }}</p>@enderror
            </div>

            {{-- Foto yang sudah ada --}}
            @if($consultation->documents->count() > 0)
            <div class="mb-6">
                <label class="form-label">Foto yang Sudah Ada</label>
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                    @foreach($consultation->documents as $doc)
                        <div class="relative group rounded-lg overflow-hidden border border-neutral-200 dark:border-neutral-700">
                            <img src="{{ Storage::url($doc->file_path) }}" alt="{{ $doc->file_name }}" class="w-full h-24 object-cover">
                            <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                <form action="{{ route('guru-bk.homeroom-consultations.documents.destroy', [$consultation, $doc]) }}" method="POST" onsubmit="return confirm('Hapus foto ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="w-8 h-8 bg-danger-500/80 hover:bg-danger-600 rounded-lg flex items-center justify-center text-white">
                                        <iconify-icon icon="solar:trash-bin-trash-bold" class="text-sm"></iconify-icon>
                                    </button>
                                </form>
                            </div>
                            <p class="text-xs text-neutral-500 truncate px-2 py-1 bg-neutral-50 dark:bg-neutral-800">{{ $doc->file_name }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
            @endif

            <div class="flex items-center gap-3">
                <button type="submit" class="btn btn-primary-600 flex items-center gap-2">
                    <iconify-icon icon="solar:diskette-bold" class="text-xl"></iconify-icon>
                    Perbarui
                </button>
                <a href="{{ route('guru-bk.homeroom-consultations.show', $consultation) }}" class="btn btn-outline-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
