@extends('layouts.app')

@section('title', 'Data Orang Tua - KonselorKita')

@section('content')

{{-- Flash Messages --}}
@if(session('success'))
    <div class="bg-success-100 dark:bg-success-600/25 text-success-600 dark:text-success-400 px-4 py-3 rounded-lg mb-4 flex items-center gap-2">
        <iconify-icon icon="solar:check-circle-bold" class="text-xl"></iconify-icon>
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="bg-danger-100 dark:bg-danger-600/25 text-danger-600 dark:text-danger-400 px-4 py-3 rounded-lg mb-4 flex items-center gap-2">
        <iconify-icon icon="solar:danger-circle-bold" class="text-xl"></iconify-icon>
        {{ session('error') }}
    </div>
@endif

<div class="card">
    <div class="card-header flex flex-wrap items-center justify-between gap-4">
        <h6 class="text-lg font-semibold mb-0">Daftar Orang Tua / Wali Murid</h6>
        <div class="flex flex-wrap items-center gap-3">
            <form action="{{ route('admin.guardians.index') }}" method="GET" class="flex items-center gap-2">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama..." class="form-control !rounded-lg">
                <button type="submit" class="btn btn-primary-600 !rounded-lg flex items-center gap-2">
                    <iconify-icon icon="ion:search-outline" class="text-xl"></iconify-icon>
                    Cari
                </button>
                @if(request('search'))
                    <a href="{{ route('admin.guardians.index') }}" class="btn btn-outline-secondary !rounded-lg">Reset</a>
                @endif
            </form>
            <a href="{{ route('admin.guardians.create') }}" class="btn btn-primary-600 flex items-center gap-2 !rounded-lg">
                <iconify-icon icon="ic:baseline-plus" class="text-xl"></iconify-icon>
                Tambah Orang Tua
            </a>
        </div>
    </div>
    <div class="card-body">
        <div class="overflow-x-auto">
            <table class="table bordered-table mb-0">
                <thead>
                    <tr>
                        <th scope="col" class="!text-center w-12">No</th>
                        <th scope="col">Nama Lengkap</th>
                        <th scope="col">Telepon</th>
                        <th scope="col">Pekerjaan</th>
                        <th scope="col">Anak / Siswa</th>
                        <th scope="col" class="!text-center w-40">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($guardians as $index => $guardian)
                        <tr>
                            <td class="!text-center">{{ $guardians->firstItem() + $index }}</td>
                            <td><span class="font-medium">{{ $guardian->full_name }}</span></td>
                            <td>{{ $guardian->phone ?? '-' }}</td>
                            <td>{{ $guardian->occupation ?? '-' }}</td>
                            <td>
                                @if($guardian->students->isNotEmpty())
                                    <div class="flex flex-wrap gap-1">
                                        @foreach($guardian->students as $student)
                                            <span class="px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-600 dark:bg-blue-900/30 dark:text-blue-400">
                                                {{ $student->full_name }} ({{ ucfirst($student->pivot->relationship) }})
                                            </span>
                                        @endforeach
                                    </div>
                                @else
                                    <span class="text-neutral-400">-</span>
                                @endif
                            </td>
                            <td class="!text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('admin.guardians.edit', $guardian) }}" class="w-8 h-8 bg-primary-50 dark:bg-primary-600/25 text-primary-600 rounded-lg flex items-center justify-center hover:bg-primary-100" title="Edit">
                                        <iconify-icon icon="solar:pen-bold" class="text-lg"></iconify-icon>
                                    </a>
                                    <form action="{{ route('admin.guardians.destroy', $guardian) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data orang tua ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="w-8 h-8 bg-danger-50 dark:bg-danger-600/25 text-danger-600 rounded-lg flex items-center justify-center hover:bg-danger-100" title="Hapus">
                                            <iconify-icon icon="solar:trash-bin-trash-bold" class="text-lg"></iconify-icon>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="!text-center py-8">
                                <div class="flex flex-col items-center gap-2 text-neutral-400">
                                    <iconify-icon icon="solar:folder-open-bold" class="text-4xl"></iconify-icon>
                                    <p class="mb-0">Belum ada data orang tua.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($guardians->hasPages())
            <div class="mt-4">
                {{ $guardians->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
