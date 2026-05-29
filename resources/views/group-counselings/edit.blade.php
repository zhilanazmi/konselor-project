@extends('layouts.app')

@section('title', 'Edit Bimbingan - KonselorKita')

@section('content')
<div class="card">
    <div class="card-header">
        <h6 class="text-lg font-semibold mb-0">Edit Sesi Bimbingan</h6>
    </div>
    <div class="card-body">
        <form action="{{ route('guru-bk.group-counselings.update', $counseling) }}" method="POST" enctype="multipart/form-data">
            @csrf @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label for="topic" class="form-label">Topik <span class="text-danger-600">*</span></label>
                    <input type="text" id="topic" name="topic" value="{{ old('topic', $counseling->topic) }}" class="form-control @error('topic') !border-danger-600 @enderror">
                    @error('topic')<p class="text-danger-600 text-sm mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="academic_year_id" class="form-label">Tahun Ajaran <span class="text-danger-600">*</span></label>
                    <select id="academic_year_id" name="academic_year_id" class="form-control @error('academic_year_id') !border-danger-600 @enderror">
                        @foreach($academicYears as $year)
                            <option value="{{ $year->id }}" {{ old('academic_year_id', $counseling->academic_year_id) == $year->id ? 'selected' : '' }}>
                                {{ $year->name }}{{ $year->is_active ? ' (Aktif)' : '' }}
                            </option>
                        @endforeach
                    </select>
                    @error('academic_year_id')<p class="text-danger-600 text-sm mt-1">{{ $message }}</p>@enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                <div>
                    <label for="service_type" class="form-label">Jenis Layanan <span class="text-danger-600">*</span></label>
                    <select id="service_type" name="service_type" class="form-control @error('service_type') !border-danger-600 @enderror">
                        <option value="group" {{ old('service_type', $counseling->service_type?->value) === 'group' ? 'selected' : '' }}>Bimbingan Kelompok</option>
                        <option value="classroom" {{ old('service_type', $counseling->service_type?->value) === 'classroom' ? 'selected' : '' }}>Bimbingan Klasikal</option>
                        <option value="large_class" {{ old('service_type', $counseling->service_type?->value) === 'large_class' ? 'selected' : '' }}>Bimbingan Kelas Besar</option>
                    </select>
                    @error('service_type')<p class="text-danger-600 text-sm mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="scheduled_at" class="form-label">Tanggal & Waktu <span class="text-danger-600">*</span></label>
                    <input type="datetime-local" id="scheduled_at" name="scheduled_at"
                        value="{{ old('scheduled_at', $counseling->scheduled_at?->format('Y-m-d\TH:i')) }}"
                        class="form-control @error('scheduled_at') !border-danger-600 @enderror">
                    @error('scheduled_at')<p class="text-danger-600 text-sm mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="status" class="form-label">Status <span class="text-danger-600">*</span></label>
                    <select id="status" name="status" class="form-control @error('status') !border-danger-600 @enderror">
                        <option value="scheduled" {{ old('status', $counseling->status) === 'scheduled' ? 'selected' : '' }}>Dijadwalkan</option>
                        <option value="ongoing" {{ old('status', $counseling->status) === 'ongoing' ? 'selected' : '' }}>Berlangsung</option>
                        <option value="completed" {{ old('status', $counseling->status) === 'completed' ? 'selected' : '' }}>Selesai</option>
                    </select>
                    @error('status')<p class="text-danger-600 text-sm mt-1">{{ $message }}</p>@enderror
                </div>
            </div>

            <div class="mb-4">
                <label for="description" class="form-label">Deskripsi / Tujuan</label>
                <textarea id="description" name="description" rows="3" class="form-control">{{ old('description', $counseling->description) }}</textarea>
            </div>
            <div class="mb-4">
                <label for="method" class="form-label">Metode / Teknik</label>
                <textarea id="method" name="method" rows="2" class="form-control">{{ old('method', $counseling->method) }}</textarea>
            </div>
            <div class="mb-4">
                <label for="result" class="form-label">Hasil Kegiatan</label>
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

            <div class="mb-4">
                <label class="form-label">Tambah Foto Dokumentasi (JPG/PNG, maks. 5 MB)</label>
                <input type="file" name="documents[]" multiple accept=".jpg,.jpeg,.png" class="form-control">
            </div>

            @if($counseling->documents->count() > 0)
            <div class="mb-6">
                <label class="form-label">Foto yang Sudah Ada</label>
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                    @foreach($counseling->documents as $doc)
                        <div class="relative group rounded-lg overflow-hidden border border-neutral-200 dark:border-neutral-700">
                            <img src="{{ Storage::url($doc->file_path) }}" alt="{{ $doc->file_name }}" class="w-full h-24 object-cover">
                            <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                <form action="{{ route('guru-bk.group-counselings.documents.destroy', [$counseling, $doc]) }}" method="POST" onsubmit="return confirm('Hapus foto ini?')">
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
                <a href="{{ route('guru-bk.group-counselings.show', $counseling) }}" class="btn btn-outline-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
