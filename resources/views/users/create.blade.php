@extends('layouts.app')

@section('title', 'Tambah Akun - KonselorKita')

@section('content')
<div class="card">
    <div class="card-header">
        <h6 class="text-lg font-semibold mb-0">Form Tambah Akun User</h6>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.users.store') }}" method="POST">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label for="name" class="form-label">Nama <span class="text-danger-600">*</span></label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}" class="form-control @error('name') !border-danger-600 @enderror">
                    @error('name')
                        <p class="text-danger-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="email" class="form-label">Email <span class="text-danger-600">*</span></label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" class="form-control @error('email') !border-danger-600 @enderror" placeholder="email@contoh.com">
                    @error('email')
                        <p class="text-danger-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                <div>
                    <label for="role" class="form-label">Role <span class="text-danger-600">*</span></label>
                    <select id="role" name="role" class="form-control @error('role') !border-danger-600 @enderror">
                        <option value="">-- Pilih Role --</option>
                        @foreach($roles as $role)
                            <option value="{{ $role->value }}" {{ old('role') === $role->value ? 'selected' : '' }}>
                                {{ $role->label() }}
                            </option>
                        @endforeach
                    </select>
                    @error('role')
                        <p class="text-danger-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="password" class="form-label">Password <span class="text-danger-600">*</span></label>
                    <input type="password" id="password" name="password" class="form-control @error('password') !border-danger-600 @enderror" placeholder="Minimal 6 karakter">
                    @error('password')
                        <p class="text-danger-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="password_confirmation" class="form-label">Konfirmasi Password <span class="text-danger-600">*</span></label>
                    <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" placeholder="Ulangi password">
                </div>
            </div>

            <div class="bg-primary-50 dark:bg-primary-600/10 rounded-lg p-4 mb-6">
                <p class="text-sm text-neutral-600 dark:text-neutral-300 mb-0">
                    <iconify-icon icon="solar:info-circle-bold" class="text-primary-600 mr-1"></iconify-icon>
                    Untuk role <strong>Siswa</strong>, <strong>Guru</strong>, dan <strong>Orang Tua</strong> disarankan membuat akun melalui menu CRUD masing-masing agar data profil otomatis terhubung.
                </p>
            </div>

            <div class="flex items-center gap-3">
                <button type="submit" class="btn btn-primary-600 flex items-center gap-2">
                    <iconify-icon icon="solar:diskette-bold" class="text-xl"></iconify-icon>
                    Simpan
                </button>
                <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
