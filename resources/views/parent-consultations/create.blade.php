@extends('layouts.app')

@section('title', 'Tambah Konsultasi Orang Tua - KonselorKita')

@section('content')
<div class="card">
    <div class="card-header">
        <h6 class="text-lg font-semibold mb-0">Form Tambah Jadwal Konsultasi Orang Tua</h6>
    </div>
    <div class="card-body">
        <form action="{{ route('guru-bk.parent-consultations.store') }}" method="POST">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label for="student_id" class="form-label">Siswa <span class="text-danger-600">*</span></label>
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

                <div>
                    <label for="guardian_id" class="form-label">Orang Tua / Wali <span class="text-danger-600">*</span></label>
                    <select id="guardian_id" name="guardian_id" class="form-control @error('guardian_id') !border-danger-600 @enderror">
                        <option value="">-- Pilih Orang Tua/Wali --</option>
                        @foreach($guardians as $guardian)
                            <option value="{{ $guardian->id }}" {{ old('guardian_id') == $guardian->id ? 'selected' : '' }}>
                                {{ $guardian->full_name }}
                            </option>
                        @endforeach
                    </select>
                    @error('guardian_id')
                        <p class="text-danger-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
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
                    <label for="scheduled_at" class="form-label">Waktu Jadwal Konsultasi <span class="text-danger-600">*</span></label>
                    <input type="datetime-local" id="scheduled_at" name="scheduled_at" value="{{ old('scheduled_at') }}" class="form-control @error('scheduled_at') !border-danger-600 @enderror">
                    @error('scheduled_at')
                        <p class="text-danger-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="requested_by" class="form-label">Pemohon <span class="text-danger-600">*</span></label>
                    <select id="requested_by" name="requested_by" class="form-control @error('requested_by') !border-danger-600 @enderror">
                        <option value="guru_bk" {{ old('requested_by') == 'guru_bk' ? 'selected' : '' }}>Panggilan Guru BK</option>
                        <option value="orang_tua" {{ old('requested_by') == 'orang_tua' ? 'selected' : '' }}>Permintaan Orang Tua</option>
                    </select>
                    @error('requested_by')
                        <p class="text-danger-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="mb-4">
                <label for="topic" class="form-label">Topik Pembahasan <span class="text-danger-600">*</span></label>
                <textarea id="topic" name="topic" rows="3" class="form-control @error('topic') !border-danger-600 @enderror" placeholder="Masalah yang ingin dibahas dengan orang tua/wali...">{{ old('topic') }}</textarea>
                @error('topic')
                    <p class="text-danger-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label for="status" class="form-label">Status <span class="text-danger-600">*</span></label>
                    <select id="status" name="status" class="form-control @error('status') !border-danger-600 @enderror">
                        <option value="requested" {{ old('status') == 'requested' ? 'selected' : '' }}>Diminta (Pending)</option>
                        <option value="scheduled" {{ old('status') == 'scheduled' ? 'selected' : '' }}>Dijadwalkan</option>
                        <option value="completed" {{ old('status') == 'completed' ? 'selected' : '' }}>Selesai</option>
                    </select>
                    @error('status')
                        <p class="text-danger-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <hr class="my-4 border-neutral-200 dark:border-neutral-700">
            <p class="text-sm font-semibold mb-3">Kolom di bawah dapat diisi setelah konsultasi berlangsung:</p>

            <div class="mb-4">
                <label for="result" class="form-label">Hasil Konsultasi / Diskusi</label>
                <textarea id="result" name="result" rows="3" class="form-control" placeholder="Rangkuman hasil pertemuan...">{{ old('result') }}</textarea>
            </div>

            <div class="mb-6">
                <label for="agreement" class="form-label">Kesepakatan / Tindak Lanjut</label>
                <textarea id="agreement" name="agreement" rows="3" class="form-control" placeholder="Kesepakatan antara sekolah dan orang tua...">{{ old('agreement') }}</textarea>
            </div>

            <div class="flex items-center gap-3">
                <button type="submit" class="btn btn-primary-600 flex items-center gap-2">
                    <iconify-icon icon="solar:diskette-bold" class="text-xl"></iconify-icon>
                    Simpan Jadwal
                </button>
                <a href="{{ route('guru-bk.parent-consultations.index') }}" class="btn btn-outline-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
