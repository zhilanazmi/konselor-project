@extends('layouts.app')

@section('title', 'Edit Jurnal Kegiatan - KonselorKita')

@section('content')

<div class="card max-w-3xl mx-auto">
    <div class="card-header">
        <h6 class="font-semibold mb-0 flex items-center gap-2">
            <iconify-icon icon="solar:pen-bold" class="text-primary-600"></iconify-icon>
            Edit Jurnal Kegiatan
        </h6>
    </div>
    <div class="card-body">
        <form action="{{ route('guru-bk.guru-bk-journals.update', $journal) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                {{-- Tahun Ajaran --}}
                <div>
                    <label for="academic_year_id" class="form-label">Tahun Ajaran <span class="text-danger-600">*</span></label>
                    <select name="academic_year_id" id="academic_year_id" class="form-control @error('academic_year_id') !border-danger-600 @enderror">
                        <option value="">-- Pilih Tahun Ajaran --</option>
                        @foreach($academicYears as $year)
                            <option value="{{ $year->id }}"
                                {{ old('academic_year_id', $journal->academic_year_id) == $year->id ? 'selected' : '' }}>
                                {{ $year->name }}{{ $year->is_active ? ' (Aktif)' : '' }}
                            </option>
                        @endforeach
                    </select>
                    @error('academic_year_id')
                        <p class="text-danger-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Tanggal --}}
                <div>
                    <label for="date" class="form-label">Tanggal Kegiatan <span class="text-danger-600">*</span></label>
                    <input type="date" name="date" id="date"
                        value="{{ old('date', $journal->date->format('Y-m-d')) }}"
                        class="form-control @error('date') !border-danger-600 @enderror">
                    @error('date')
                        <p class="text-danger-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Jenis Kegiatan --}}
            <div class="mb-4">
                <label for="activity_type" class="form-label">Jenis Kegiatan <span class="text-danger-600">*</span></label>
                <select name="activity_type" id="activity_type" class="form-control @error('activity_type') !border-danger-600 @enderror">
                    <option value="">-- Pilih Jenis Kegiatan --</option>
                    @foreach($activityTypeLabels as $value => $label)
                        <option value="{{ $value }}"
                            {{ old('activity_type', $journal->activity_type) === $value ? 'selected' : '' }}>
                            {{ $label }}
                        </option>
                    @endforeach
                </select>
                @error('activity_type')
                    <p class="text-danger-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Judul --}}
            <div class="mb-4">
                <label for="title" class="form-label">Judul Kegiatan <span class="text-danger-600">*</span></label>
                <input type="text" name="title" id="title"
                    value="{{ old('title', $journal->title) }}"
                    class="form-control @error('title') !border-danger-600 @enderror">
                @error('title')
                    <p class="text-danger-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Deskripsi --}}
            <div class="mb-4">
                <label for="description" class="form-label">Deskripsi Kegiatan</label>
                <textarea name="description" id="description" rows="3"
                    class="form-control @error('description') !border-danger-600 @enderror">{{ old('description', $journal->description) }}</textarea>
                @error('description')
                    <p class="text-danger-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                {{-- Sasaran --}}
                <div>
                    <label for="target_group" class="form-label">Sasaran</label>
                    <input type="text" name="target_group" id="target_group"
                        value="{{ old('target_group', $journal->target_group) }}"
                        class="form-control @error('target_group') !border-danger-600 @enderror">
                    @error('target_group')
                        <p class="text-danger-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Lokasi --}}
                <div>
                    <label for="location" class="form-label">Lokasi</label>
                    <input type="text" name="location" id="location"
                        value="{{ old('location', $journal->location) }}"
                        class="form-control @error('location') !border-danger-600 @enderror">
                    @error('location')
                        <p class="text-danger-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Durasi --}}
                <div>
                    <label for="duration_minutes" class="form-label">Durasi (menit)</label>
                    <input type="number" name="duration_minutes" id="duration_minutes"
                        value="{{ old('duration_minutes', $journal->duration_minutes) }}"
                        min="1" max="9999"
                        class="form-control @error('duration_minutes') !border-danger-600 @enderror">
                    @error('duration_minutes')
                        <p class="text-danger-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Catatan --}}
            <div class="mb-6">
                <label for="notes" class="form-label">Catatan Tambahan</label>
                <textarea name="notes" id="notes" rows="2"
                    class="form-control @error('notes') !border-danger-600 @enderror">{{ old('notes', $journal->notes) }}</textarea>
                @error('notes')
                    <p class="text-danger-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center gap-3">
                <button type="submit" class="btn btn-primary-600 !rounded-lg flex items-center gap-2">
                    <iconify-icon icon="solar:diskette-bold" class="text-lg"></iconify-icon>
                    Simpan Perubahan
                </button>
                <a href="{{ route('guru-bk.guru-bk-journals.show', $journal) }}" class="btn btn-outline-secondary !rounded-lg">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>

@endsection
