@extends('layouts.app')

@section('title', 'Edit Konseling Individual - KonselorKita')

@section('content')
<div class="card">
    <div class="card-header">
        <h6 class="text-lg font-semibold mb-0">Edit Sesi Konseling Individual</h6>
    </div>
    <div class="card-body">
        <form action="{{ route('guru-bk.individual-counselings.update', $counseling) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label for="student_id" class="form-label">Siswa <span class="text-danger-600">*</span></label>
                    <select id="student_id" name="student_id" class="form-control @error('student_id') !border-danger-600 @enderror">
                        <option value="">-- Pilih Siswa --</option>
                        @foreach($students as $student)
                            <option value="{{ $student->id }}" {{ old('student_id', $counseling->student_id) == $student->id ? 'selected' : '' }}>
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
                            <option value="{{ $year->id }}" {{ old('academic_year_id', $counseling->academic_year_id) == $year->id ? 'selected' : '' }}>
                                {{ $year->name }}{{ $year->is_active ? ' (Aktif)' : '' }}
                            </option>
                        @endforeach
                    </select>
                    @error('academic_year_id')
                        <p class="text-danger-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                <div>
                    <label for="scheduled_at" class="form-label">Tanggal & Waktu Sesi <span class="text-danger-600">*</span></label>
                    <input type="datetime-local" id="scheduled_at" name="scheduled_at"
                        value="{{ old('scheduled_at', $counseling->scheduled_at?->format('Y-m-d\TH:i')) }}"
                        class="form-control @error('scheduled_at') !border-danger-600 @enderror">
                    @error('scheduled_at')
                        <p class="text-danger-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="category" class="form-label">Kategori <span class="text-danger-600">*</span></label>
                    <select id="category" name="category" class="form-control @error('category') !border-danger-600 @enderror">
                        <option value="">-- Pilih Kategori --</option>
                        <option value="pribadi" {{ old('category', $counseling->category) === 'pribadi' ? 'selected' : '' }}>Pribadi</option>
                        <option value="sosial" {{ old('category', $counseling->category) === 'sosial' ? 'selected' : '' }}>Sosial</option>
                        <option value="belajar" {{ old('category', $counseling->category) === 'belajar' ? 'selected' : '' }}>Belajar</option>
                        <option value="karir" {{ old('category', $counseling->category) === 'karir' ? 'selected' : '' }}>Karir</option>
                    </select>
                    @error('category')
                        <p class="text-danger-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="status" class="form-label">Status <span class="text-danger-600">*</span></label>
                    <select id="status" name="status" class="form-control @error('status') !border-danger-600 @enderror">
                        <option value="">-- Pilih Status --</option>
                        <option value="scheduled" {{ old('status', $counseling->status) === 'scheduled' ? 'selected' : '' }}>Dijadwalkan</option>
                        <option value="ongoing" {{ old('status', $counseling->status) === 'ongoing' ? 'selected' : '' }}>Berlangsung</option>
                        <option value="completed" {{ old('status', $counseling->status) === 'completed' ? 'selected' : '' }}>Selesai</option>
                        <option value="followed_up" {{ old('status', $counseling->status) === 'followed_up' ? 'selected' : '' }}>Tindak Lanjut</option>
                    </select>
                    @error('status')
                        <p class="text-danger-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="mb-4">
                <label for="problem_description" class="form-label">Deskripsi Masalah <span class="text-danger-600">*</span></label>
                <textarea id="problem_description" name="problem_description" rows="4" class="form-control @error('problem_description') !border-danger-600 @enderror">{{ old('problem_description', $counseling->problem_description) }}</textarea>
                @error('problem_description')
                    <p class="text-danger-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="approach" class="form-label">Pendekatan / Teknik Konseling</label>
                <textarea id="approach" name="approach" rows="3" class="form-control">{{ old('approach', $counseling->approach) }}</textarea>
            </div>

            <div class="mb-4">
                <label for="result" class="form-label">Hasil Konseling</label>
                <textarea id="result" name="result" rows="3" class="form-control">{{ old('result', $counseling->result) }}</textarea>
            </div>

            <div class="mb-4">
                <label for="evaluation" class="form-label">Evaluasi</label>
                <textarea id="evaluation" name="evaluation" rows="3" class="form-control">{{ old('evaluation', $counseling->evaluation) }}</textarea>
            </div>

            <div class="mb-4">
                <label for="follow_up" class="form-label">Tindak Lanjut</label>
                <textarea id="follow_up" name="follow_up" rows="3" class="form-control">{{ old('follow_up', $counseling->follow_up) }}</textarea>
            </div>

            <div class="mb-6">
                <label for="follow_up_plan" class="form-label">Rencana Tindak Lanjut</label>
                <textarea id="follow_up_plan" name="follow_up_plan" rows="2" class="form-control">{{ old('follow_up_plan', $counseling->follow_up_plan) }}</textarea>
            </div>

            {{-- Upload Dokumentasi --}}
            <div class="mb-4">
                <label class="form-label">Tambah Foto Dokumentasi (JPG/PNG, maks. 5 MB per file)</label>
                <input type="file" name="documents[]" multiple accept=".jpg,.jpeg,.png"
                    class="form-control @error('documents.*') !border-danger-600 @enderror">
                @error('documents.*')
                    <p class="text-danger-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Existing Documents --}}
            @if($counseling->documents->count() > 0)
                <div class="mb-6">
                    <label class="form-label">Foto yang Sudah Ada</label>
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                        @foreach($counseling->documents as $doc)
                            <div class="relative group rounded-lg overflow-hidden border border-neutral-200 dark:border-neutral-700">
                                <img src="{{ Storage::url($doc->file_path) }}" alt="{{ $doc->file_name }}" class="w-full h-24 object-cover">
                                <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                    <form action="{{ route('guru-bk.individual-counselings.documents.destroy', [$counseling, $doc]) }}" method="POST" onsubmit="return confirm('Hapus foto ini?')">
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
                <a href="{{ route('guru-bk.individual-counselings.show', $counseling) }}" class="btn btn-outline-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
