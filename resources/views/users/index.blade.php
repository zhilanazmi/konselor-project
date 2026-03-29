@extends('layouts.app')

@section('title', 'Manajemen Akun - KonselorKita')

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
        <h6 class="text-lg font-semibold mb-0">Manajemen Akun User</h6>
        <div class="flex flex-wrap items-center gap-3">
            <form action="{{ route('admin.users.index') }}" method="GET" class="flex items-center gap-2">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama / email..." class="form-control !rounded-lg">
                <select name="role" class="form-control !rounded-lg !w-auto">
                    <option value="">Semua Role</option>
                    @foreach($roles as $role)
                        <option value="{{ $role->value }}" {{ request('role') === $role->value ? 'selected' : '' }}>
                            {{ $role->label() }}
                        </option>
                    @endforeach
                </select>
                <button type="submit" class="btn btn-primary-600 !rounded-lg flex items-center gap-2">
                    <iconify-icon icon="ion:search-outline" class="text-xl"></iconify-icon>
                    Filter
                </button>
                @if(request('search') || request('role'))
                    <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary !rounded-lg">Reset</a>
                @endif
            </form>
            <a href="{{ route('admin.users.create') }}" class="btn btn-primary-600 flex items-center gap-2 !rounded-lg">
                <iconify-icon icon="ic:baseline-plus" class="text-xl"></iconify-icon>
                Tambah Akun
            </a>
        </div>
    </div>
    <div class="card-body">
        <div class="overflow-x-auto">
            <table class="table bordered-table mb-0">
                <thead>
                    <tr>
                        <th scope="col" class="!text-center w-12">No</th>
                        <th scope="col">Nama</th>
                        <th scope="col">Email</th>
                        <th scope="col" class="!text-center">Role</th>
                        <th scope="col" class="!text-center w-40">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $index => $user)
                        <tr>
                            <td class="!text-center">{{ $users->firstItem() + $index }}</td>
                            <td><span class="font-medium">{{ $user->name }}</span></td>
                            <td>{{ $user->email }}</td>
                            <td class="!text-center">
                                @php
                                    $badgeClass = match($user->role) {
                                        \App\Enums\UserRole::Admin => 'bg-red-100 text-red-600 dark:bg-red-900/30 dark:text-red-400',
                                        \App\Enums\UserRole::GuruBk => 'bg-emerald-100 text-emerald-600 dark:bg-emerald-900/30 dark:text-emerald-400',
                                        \App\Enums\UserRole::Guru => 'bg-blue-100 text-blue-600 dark:bg-blue-900/30 dark:text-blue-400',
                                        \App\Enums\UserRole::OrangTua => 'bg-amber-100 text-amber-600 dark:bg-amber-900/30 dark:text-amber-400',
                                        \App\Enums\UserRole::Siswa => 'bg-purple-100 text-purple-600 dark:bg-purple-900/30 dark:text-purple-400',
                                    };
                                @endphp
                                <span class="px-2 py-0.5 rounded text-xs font-medium {{ $badgeClass }}">
                                    {{ $user->role->label() }}
                                </span>
                            </td>
                            <td class="!text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('admin.users.edit', $user) }}" class="w-8 h-8 bg-primary-50 dark:bg-primary-600/25 text-primary-600 rounded-lg flex items-center justify-center hover:bg-primary-100" title="Edit">
                                        <iconify-icon icon="solar:pen-bold" class="text-lg"></iconify-icon>
                                    </a>
                                    @if($user->id !== auth()->id())
                                        <form action="{{ route('admin.users.destroy', $user) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus akun ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="w-8 h-8 bg-danger-50 dark:bg-danger-600/25 text-danger-600 rounded-lg flex items-center justify-center hover:bg-danger-100" title="Hapus">
                                                <iconify-icon icon="solar:trash-bin-trash-bold" class="text-lg"></iconify-icon>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="!text-center py-8">
                                <div class="flex flex-col items-center gap-2 text-neutral-400">
                                    <iconify-icon icon="solar:folder-open-bold" class="text-4xl"></iconify-icon>
                                    <p class="mb-0">Belum ada data akun.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($users->hasPages())
            <div class="mt-4">
                {{ $users->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
