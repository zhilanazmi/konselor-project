@extends('layouts.app')

@section('title', 'Tambah Guru - KonselorKita')

@section('content')
<div class="card">
    <div class="card-header">
        <h6 class="text-lg font-semibold mb-0">Form Tambah Guru</h6>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.teachers.store') }}" method="POST">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label for="nip" class="form-label">NIP <span class="text-danger-600">*</span></label>
                    <input type="text" id="nip" name="nip" value="{{ old('nip') }}" class="form-control @error('nip') !border-danger-600 @enderror" placeholder="Nomor Induk Pegawai">
                    @error('nip')
                        <p class="text-danger-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="full_name" class="form-label">Nama Lengkap <span class="text-danger-600">*</span></label>
                    <input type="text" id="full_name" name="full_name" value="{{ old('full_name') }}" class="form-control @error('full_name') !border-danger-600 @enderror">
                    @error('full_name')
                        <p class="text-danger-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                <div>
                    <label for="email" class="form-label">Email <span class="text-danger-600">*</span></label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" class="form-control @error('email') !border-danger-600 @enderror" placeholder="email@sekolah.sch.id">
                    @error('email')
                        <p class="text-danger-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="subject" class="form-label">Mata Pelajaran <span class="text-neutral-400 text-sm">(opsional)</span></label>
                    <input type="text" id="subject" name="subject" value="{{ old('subject') }}" class="form-control" placeholder="cth: Matematika">
                </div>
            </div>

            <div class="bg-primary-50 dark:bg-primary-600/10 rounded-lg p-4 mb-6">
                <p class="text-sm text-neutral-600 dark:text-neutral-300 mb-0">
                    <iconify-icon icon="solar:info-circle-bold" class="text-primary-600 mr-1"></iconify-icon>
                    Akun login guru akan otomatis dibuat dengan password = <strong>NIP</strong>.
                </p>
            </div>

            <div class="flex items-center gap-3">
                <button type="submit" class="btn btn-primary-600 flex items-center gap-2">
                    <iconify-icon icon="solar:diskette-bold" class="text-xl"></iconify-icon>
                    Simpan
                </button>
                <a href="{{ route('admin.teachers.index') }}" class="btn btn-outline-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
