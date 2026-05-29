@extends('layouts.app')

@section('title', 'Tambah Konseling Kelompok - KonselorKita')

@section('content')
<div class="card">
    <div class="card-header">
        <h6 class="text-lg font-semibold mb-0">Form Tambah Sesi Konseling Kelompok</h6>
    </div>
    <div class="card-body">
        <form action="{{ route('guru-bk.group-counselings.store') }}" method="POST">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label for="topic" class="form-label">Topik <span class="text-danger-600">*</span></label>
                    <input type="text" id="topic" name="topic" value="{{ old('topic') }}" class="form-control @error('topic') !border-danger-600 @enderror" placeholder="Topik konseling kelompok">
                    @error('topic')
                        <p class="text-danger-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

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
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label for="scheduled_at" class="form-label">Tanggal & Waktu Sesi <span class="text-danger-600">*</span></label>
                    <input type="datetime-local" id="scheduled_at" name="scheduled_at" value="{{ old('scheduled_at') }}" class="form-control @error('scheduled_at') !border-danger-600 @enderror">
                    @error('scheduled_at')
                        <p class="text-danger-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="status" class="form-label">Status <span class="text-danger-600">*</span></label>
                    <select id="status" name="status" class="form-control @error('status') !border-danger-600 @enderror">
                        <option value="">-- Pilih Status --</option>
                        <option value="scheduled" {{ old('status', 'scheduled') === 'scheduled' ? 'selected' : '' }}>Dijadwalkan</option>
                        <option value="ongoing" {{ old('status') === 'ongoing' ? 'selected' : '' }}>Berlangsung</option>
                        <option value="completed" {{ old('status') === 'completed' ? 'selected' : '' }}>Selesai</option>
                    </select>
                    @error('status')
                        <p class="text-danger-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="mb-4">
                <label for="description" class="form-label">Deskripsi / Tujuan</label>
                <textarea id="description" name="description" rows="3" class="form-control" placeholder="Deskripsi dan tujuan kegiatan konseling kelompok...">{{ old('description') }}</textarea>
            </div>

            <div class="mb-4">
                <label for="method" class="form-label">Metode / Teknik</label>
                <textarea id="method" name="method" rows="2" class="form-control" placeholder="Metode atau teknik yang digunakan...">{{ old('method') }}</textarea>
            </div>

            <div class="mb-4">
                <label for="result" class="form-label">Hasil Kegiatan</label>
                <textarea id="result" name="result" rows="3" class="form-control" placeholder="Hasil yang dicapai...">{{ old('result') }}</textarea>
            </div>

            <div class="mb-6">
                <label for="evaluation" class="form-label">Evaluasi</label>
                <textarea id="evaluation" name="evaluation" rows="2" class="form-control" placeholder="Evaluasi kegiatan konseling kelompok...">{{ old('evaluation') }}</textarea>
            </div>

            {{-- Peserta Awal (Opsional) --}}
            <div class="mb-6">
                <label class="form-label">Peserta (Opsional)</label>
                <p class="text-sm text-neutral-500 dark:text-neutral-400 mb-2">Pilih peserta awal. Peserta juga bisa ditambahkan setelah sesi dibuat.</p>
                <div class="border border-neutral-200 dark:border-neutral-700 rounded-lg p-3 max-h-56 overflow-y-auto">
                    @foreach($students as $student)
                        <label class="flex items-center gap-2 py-1.5 cursor-pointer hover:bg-neutral-50 dark:hover:bg-neutral-800 px-2 rounded">
                            <input type="checkbox" name="student_ids[]" value="{{ $student->id }}"
                                {{ in_array($student->id, old('student_ids', [])) ? 'checked' : '' }}
                                class="w-4 h-4 accent-primary-600">
                            <span class="text-sm">{{ $student->full_name }} <span class="text-neutral-400">({{ $student->nis }})</span></span>
                        </label>
                    @endforeach
                    @if($students->isEmpty())
                        <p class="text-neutral-400 text-sm text-center py-2">Belum ada data siswa.</p>
                    @endif
                </div>
            </div>

            <div class="flex items-center gap-3">
                <button type="submit" class="btn btn-primary-600 flex items-center gap-2">
                    <iconify-icon icon="solar:diskette-bold" class="text-xl"></iconify-icon>
                    Simpan
                </button>
                <a href="{{ route('guru-bk.group-counselings.index') }}" class="btn btn-outline-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
