@extends('layouts.app')

@section('title', 'Edit Guru - KonselorKita')

@section('content')
<div class="card">
    <div class="card-header">
        <h6 class="text-lg font-semibold mb-0">Edit Guru: {{ $teacher->full_name }}</h6>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.teachers.update', $teacher) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label for="nip" class="form-label">NIP <span class="text-danger-600">*</span></label>
                    <input type="text" id="nip" name="nip" value="{{ old('nip', $teacher->nip) }}" class="form-control @error('nip') !border-danger-600 @enderror">
                    @error('nip')
                        <p class="text-danger-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="full_name" class="form-label">Nama Lengkap <span class="text-danger-600">*</span></label>
                    <input type="text" id="full_name" name="full_name" value="{{ old('full_name', $teacher->full_name) }}" class="form-control @error('full_name') !border-danger-600 @enderror">
                    @error('full_name')
                        <p class="text-danger-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                <div>
                    <label for="email" class="form-label">Email <span class="text-danger-600">*</span></label>
                    <input type="email" id="email" name="email" value="{{ old('email', $teacher->user->email) }}" class="form-control @error('email') !border-danger-600 @enderror">
                    @error('email')
                        <p class="text-danger-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="subject" class="form-label">Mata Pelajaran <span class="text-neutral-400 text-sm">(opsional)</span></label>
                    <input type="text" id="subject" name="subject" value="{{ old('subject', $teacher->subject) }}" class="form-control">
                </div>
            </div>

            <div class="flex items-center gap-3">
                <button type="submit" class="btn btn-primary-600 flex items-center gap-2">
                    <iconify-icon icon="solar:diskette-bold" class="text-xl"></iconify-icon>
                    Perbarui
                </button>
                <a href="{{ route('admin.teachers.index') }}" class="btn btn-outline-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
