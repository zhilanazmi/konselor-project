@extends('layouts.app')

@section('title', 'Edit Tahun Ajaran - KonselorKita')

@section('content')

<div class="card">
    <div class="card-header">
        <h6 class="text-lg font-semibold mb-0">Edit Tahun Ajaran: {{ $academicYear->name }}</h6>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.academic-years.update', $academicYear) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                {{-- Nama --}}
                <div>
                    <label for="name" class="form-label">Nama Tahun Ajaran <span class="text-danger-600">*</span></label>
                    <input type="text" id="name" name="name" value="{{ old('name', $academicYear->name) }}" class="form-control @error('name') !border-danger-600 @enderror" placeholder="Contoh: 2025/2026">
                    @error('name')
                        <p class="text-danger-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                {{-- Tanggal Mulai --}}
                <div>
                    <label for="start_date" class="form-label">Tanggal Mulai <span class="text-danger-600">*</span></label>
                    <input type="date" id="start_date" name="start_date" value="{{ old('start_date', $academicYear->start_date->format('Y-m-d')) }}" class="form-control @error('start_date') !border-danger-600 @enderror">
                    @error('start_date')
                        <p class="text-danger-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Tanggal Selesai --}}
                <div>
                    <label for="end_date" class="form-label">Tanggal Selesai <span class="text-danger-600">*</span></label>
                    <input type="date" id="end_date" name="end_date" value="{{ old('end_date', $academicYear->end_date->format('Y-m-d')) }}" class="form-control @error('end_date') !border-danger-600 @enderror">
                    @error('end_date')
                        <p class="text-danger-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Status Aktif --}}
            <div class="mb-6">
                <div class="flex items-center gap-3">
                    <input type="hidden" name="is_active" value="0">
                    <input type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', $academicYear->is_active) ? 'checked' : '' }} class="form-check-input">
                    <label for="is_active" class="form-check-label">Set sebagai tahun ajaran aktif</label>
                </div>
                <p class="text-neutral-400 text-sm mt-1">Jika dicentang, tahun ajaran lain akan dinonaktifkan secara otomatis.</p>
            </div>

            {{-- Buttons --}}
            <div class="flex items-center gap-3">
                <button type="submit" class="btn btn-primary-600 flex items-center gap-2">
                    <iconify-icon icon="solar:diskette-bold" class="text-xl"></iconify-icon>
                    Perbarui
                </button>
                <a href="{{ route('admin.academic-years.index') }}" class="btn btn-outline-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
