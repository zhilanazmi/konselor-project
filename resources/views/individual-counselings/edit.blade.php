@extends('layouts.app')

@section('title', 'Edit Konseling Individual - KonselorKita')

@section('content')
<div class="card">
    <div class="card-header">
        <h6 class="text-lg font-semibold mb-0">Edit Sesi Konseling Individual</h6>
    </div>
    <div class="card-body">
        <form action="{{ route('guru-bk.individual-counselings.update', $counseling) }}" method="POST">
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

            <div class="mb-6">
                <label for="follow_up_plan" class="form-label">Rencana Tindak Lanjut</label>
                <textarea id="follow_up_plan" name="follow_up_plan" rows="3" class="form-control">{{ old('follow_up_plan', $counseling->follow_up_plan) }}</textarea>
            </div>

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
