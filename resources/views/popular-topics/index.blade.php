@extends('layouts.app')

@section('title', 'Kelola Topik Populer - KonselorKita')

@section('content')

@if(session('success'))
    <div class="bg-success-100 dark:bg-success-600/25 text-success-600 dark:text-success-400 px-4 py-3 rounded-lg mb-4 flex items-center gap-2">
        <iconify-icon icon="solar:check-circle-bold" class="text-xl"></iconify-icon>
        {{ session('success') }}
    </div>
@endif

<div class="card">
    <div class="card-header flex flex-wrap items-center justify-between gap-4">
        <div>
            <h6 class="text-lg font-semibold mb-1">Kelola Topik Populer</h6>
            <p class="text-sm text-neutral-500 dark:text-neutral-400 mb-0">Kelola topik yang ditampilkan di halaman landing page</p>
        </div>
        <a href="{{ route('guru-bk.popular-topics.create') }}" class="btn btn-primary-600 flex items-center gap-2 !rounded-lg">
            <iconify-icon icon="ic:baseline-plus" class="text-xl"></iconify-icon>
            Tambah Topik
        </a>
    </div>

    {{-- Filter Bar --}}
    <div class="card-body border-b border-neutral-200 dark:border-neutral-700 pb-4">
        <form action="{{ route('guru-bk.popular-topics.index') }}" method="GET" class="flex flex-wrap items-end gap-3">
            <div class="flex-1 min-w-[200px]">
                <label class="form-label text-xs">Cari Topik</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Judul topik..." class="form-control !rounded-lg">
            </div>
            <div class="flex gap-2">
                <button type="submit" class="btn btn-primary-600 !rounded-lg flex items-center gap-2">
                    <iconify-icon icon="ion:search-outline" class="text-xl"></iconify-icon>
                    Cari
                </button>
                @if(request('search'))
                    <a href="{{ route('guru-bk.popular-topics.index') }}" class="btn btn-outline-secondary !rounded-lg">Reset</a>
                @endif
            </div>
        </form>
    </div>

    <div class="card-body">
        <div class="overflow-x-auto">
            <table class="table bordered-table mb-0">
                <thead>
                    <tr>
                        <th scope="col" class="!text-center w-12">No</th>
                        <th scope="col" class="w-20">Gambar</th>
                        <th scope="col">Judul</th>
                        <th scope="col">Deskripsi</th>
                        <th scope="col" class="!text-center">Urutan</th>
                        <th scope="col" class="!text-center">Status</th>
                        <th scope="col" class="!text-center w-40">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($topics as $index => $topic)
                        <tr>
                            <td class="!text-center">{{ $topics->firstItem() + $index }}</td>
                            <td>
                                @if($topic->image)
                                    <img src="{{ asset('storage/' . $topic->image) }}" alt="{{ $topic->title }}" class="w-16 h-16 object-cover rounded-lg">
                                @else
                                    <div class="w-16 h-16 rounded-lg flex items-center justify-center" style="background-color: {{ $topic->icon_color }}20;">
                                        <iconify-icon icon="{{ $topic->icon ?? 'solar:book-bold' }}" class="text-2xl" style="color: {{ $topic->icon_color }};"></iconify-icon>
                                    </div>
                                @endif
                            </td>
                            <td>
                                <span class="font-medium">{{ $topic->title }}</span>
                            </td>
                            <td>
                                <p class="text-sm text-neutral-500 dark:text-neutral-400 mb-0">{{ Str::limit($topic->description, 80) }}</p>
                            </td>
                            <td class="!text-center">
                                <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-neutral-100 dark:bg-neutral-700 text-sm font-medium">
                                    {{ $topic->order }}
                                </span>
                            </td>
                            <td class="!text-center">
                                <form action="{{ route('guru-bk.popular-topics.toggle-status', $topic) }}" method="POST" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="px-3 py-1 rounded text-xs font-medium {{ $topic->is_active ? 'bg-success-100 text-success-600 dark:bg-success-600/25 dark:text-success-400' : 'bg-neutral-100 text-neutral-600 dark:bg-neutral-700 dark:text-neutral-400' }}">
                                        {{ $topic->is_active ? 'Aktif' : 'Nonaktif' }}
                                    </button>
                                </form>
                            </td>
                            <td class="!text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('guru-bk.popular-topics.edit', $topic) }}" class="w-8 h-8 bg-primary-50 dark:bg-primary-600/25 text-primary-600 rounded-lg flex items-center justify-center hover:bg-primary-100" title="Edit">
                                        <iconify-icon icon="solar:pen-bold" class="text-lg"></iconify-icon>
                                    </a>
                                    <form action="{{ route('guru-bk.popular-topics.destroy', $topic) }}" method="POST" onsubmit="return confirm('Hapus topik ini?')">
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
                            <td colspan="7" class="!text-center py-8">
                                <div class="flex flex-col items-center gap-2 text-neutral-400">
                                    <iconify-icon icon="solar:folder-open-bold" class="text-4xl"></iconify-icon>
                                    <p class="mb-0">Belum ada topik populer.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($topics->hasPages())
            <div class="mt-4">
                {{ $topics->links() }}
            </div>
        @endif
    </div>
</div>

@endsection
