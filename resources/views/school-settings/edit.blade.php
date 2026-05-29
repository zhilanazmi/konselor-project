@extends('layouts.app')

@section('title', 'Pengaturan Sekolah - KonselorKita')

@section('content')

@if(session('success'))
    <div class="bg-success-100 dark:bg-success-600/25 text-success-600 dark:text-success-400 px-4 py-3 rounded-lg mb-4 flex items-center gap-2">
        <iconify-icon icon="solar:check-circle-bold" class="text-xl"></iconify-icon>{{ session('success') }}
    </div>
@endif

<div class="card">
    <div class="card-header">
        <h6 class="text-lg font-semibold mb-0">Pengaturan Data Kepala Sekolah</h6>
        <p class="text-sm text-neutral-500 dark:text-neutral-400 mt-1 mb-0">Data ini akan ditampilkan pada kolom tanda tangan di semua laporan PDF.</p>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.school-settings.update') }}" method="POST">
            @csrf @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                <div>
                    <label for="principal_name" class="form-label">Nama Lengkap Kepala Sekolah <span class="text-danger-600">*</span></label>
                    <input type="text" id="principal_name" name="principal_name"
                        value="{{ old('principal_name', $principalName) }}"
                        class="form-control @error('principal_name') !border-danger-600 @enderror"
                        placeholder="Nama lengkap kepala sekolah">
                    @error('principal_name')<p class="text-danger-600 text-sm mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="principal_nip" class="form-label">NIP Kepala Sekolah <span class="text-danger-600">*</span></label>
                    <input type="text" id="principal_nip" name="principal_nip"
                        value="{{ old('principal_nip', $principalNip) }}"
                        class="form-control @error('principal_nip') !border-danger-600 @enderror"
                        placeholder="NIP kepala sekolah">
                    @error('principal_nip')<p class="text-danger-600 text-sm mt-1">{{ $message }}</p>@enderror
                </div>
            </div>

            <div class="flex items-center gap-3">
                <button type="submit" class="btn btn-primary-600 flex items-center gap-2">
                    <iconify-icon icon="solar:diskette-bold" class="text-xl"></iconify-icon>Simpan Pengaturan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
