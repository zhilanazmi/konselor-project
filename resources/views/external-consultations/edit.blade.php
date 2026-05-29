@extends('layouts.app')

@section('title', 'Edit Konsultasi Pihak Luar - KonselorKita')

@section('content')
<div class="card">
    <div class="card-header">
        <h6 class="text-lg font-semibold mb-0">Edit Konsultasi Pihak Luar</h6>
    </div>
    <div class="card-body">
        <form action="{{ route('guru-bk.external-consultations.update', $consultation) }}" method="POST" enctype="multipart/form-data">
            @csrf @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label for="external_party_name" class="form-label">Nama Pihak Luar <span class="text-danger-600">*</span></label>
                    <input type="text" id="external_party_name" name="external_party_name"
                        value="{{ old('external_party_name', $consultation->external_party_name) }}"
                        class="form-control @error('external_party_name') !border-danger-600 @enderror">
                    @error('external_party_name')<p class="text-danger-600 text-sm mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="external_party_role" class="form-label">Peran / Hubungan <span class="text-danger-600">*</span></label>
                    <input type="text" id="external_party_role" name="external_party_role"
                        value="{{ old('external_party_role', $consultation->external_party_role) }}"
                        class="form-control @error('external_party_role') !border-danger-600 @enderror">
                    @error('external_party_role')<p class="text-danger-600 text-sm mt-1">{{ $message }}</p>@enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label for="consultation_date" class="form-label">Tanggal Konsultasi <span class="text-danger-600">*</span></label>
                    <input type="datetime-local" id="consultation_date" name="consultation_date"
                        value="{{ old('consultation_date', $consultation->consultation_date->format('Y-m-d\TH:i')) }}"
                        class="form-control @error('consultation_date') !border-danger-600 @enderror">
                    @error('consultation_date')<p class="text-danger-600 text-sm mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="academic_year_id" class="form-label">Tahun Ajaran <span class="text-danger-600">*</span></label>
                    <select id="academic_year_id" name="academic_year_id" class="form-control @error('academic_year_id') !border-danger-600 @enderror">
                        @foreach($academicYears as $year)
                            <option value="{{ $year->id }}" {{ old('academic_year_id', $consultation->academic_year_id) == $year->id ? 'selected' : '' }}>
                                {{ $year->name }}{{ $year->is_active ? ' (Aktif)' : '' }}
                            </option>
                        @endforeach
                    </select>
                    @error('academic_year_id')<p class="text-danger-600 text-sm mt-1">{{ $message }}</p>@enderror
                </div>
            </div>

            <div class="mb-4">
                <label for="student_id" class="form-label">Siswa Terkait <span class="text-neutral-400 text-xs">(opsional)</span></label>
                <select id="student_id" name="student_id" class="form-control">
                    <option value="">-- Tidak ada --</option>
                    @foreach($students as $student)
                        <option value="{{ $student->id }}" {{ old('student_id', $consultation->student_id) == $student->id ? 'selected' : '' }}>
                            {{ $student->full_name }} ({{ $student->nis }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-4">
                <label for="topic" class="form-label">Topik Konsultasi <span class="text-danger-600">*</span></label>
                <textarea id="topic" name="topic" rows="3" class="form-control @error('topic') !border-danger-600 @enderror">{{ old('topic', $consultation->topic) }}</textarea>
                @error('topic')<p class="text-danger-600 text-sm mt-1">{{ $message }}</p>@enderror
            </div>
            <div class="mb-4">
                <label for="notes" class="form-label">Catatan</label>
                <textarea id="notes" name="notes" rows="3" class="form-control">{{ old('notes', $consultation->notes) }}</textarea>
            </div>
            <div class="mb-4">
                <label for="evaluation" class="form-label">Evaluasi</label>
                <textarea id="evaluation" name="evaluation" rows="3" class="form-control">{{ old('evaluation', $consultation->evaluation) }}</textarea>
            </div>
            <div class="mb-4">
                <label for="follow_up" class="form-label">Tindak Lanjut</label>
                <textarea id="follow_up" name="follow_up" rows="3" class="form-control">{{ old('follow_up', $consultation->follow_up) }}</textarea>
            </div>

            <div class="mb-4">
                <label class="form-label">Tambah Foto Dokumentasi (JPG/PNG, maks. 5 MB)</label>
                <input type="file" name="documents[]" multiple accept=".jpg,.jpeg,.png" class="form-control">
            </div>

            @if($consultation->documents->count() > 0)
            <div class="mb-6">
                <label class="form-label">Foto yang Sudah Ada</label>
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                    @foreach($consultation->documents as $doc)
                        <div class="relative group rounded-lg overflow-hidden border border-neutral-200 dark:border-neutral-700">
                            <img src="{{ Storage::url($doc->file_path) }}" alt="{{ $doc->file_name }}" class="w-full h-24 object-cover">
                            <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                <form action="{{ route('guru-bk.external-consultations.documents.destroy', [$consultation, $doc]) }}" method="POST" onsubmit="return confirm('Hapus foto ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="w-8 h-8 bg-danger-500/80 hover:bg-danger-600 rounded-lg flex items-center justify-center text-white">
                                        <iconify-icon icon="solar:trash-bin-trash-bold" class="text-sm"></iconify-icon>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            @endif

            <div class="flex items-center gap-3">
                <button type="submit" class="btn btn-primary-600 flex items-center gap-2">
                    <iconify-icon icon="solar:diskette-bold" class="text-xl"></iconify-icon>Perbarui
                </button>
                <a href="{{ route('guru-bk.external-consultations.show', $consultation) }}" class="btn btn-outline-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
