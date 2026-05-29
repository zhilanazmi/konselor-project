@extends('layouts.app')

@section('title', 'Detail Konseling Individual - KonselorKita')

@section('content')

@if(session('success'))
    <div class="bg-success-100 dark:bg-success-600/25 text-success-600 dark:text-success-400 px-4 py-3 rounded-lg mb-4 flex items-center gap-2">
        <iconify-icon icon="solar:check-circle-bold" class="text-xl"></iconify-icon>
        {{ session('success') }}
    </div>
@endif

{{-- Header Card --}}
<div class="card mb-4">
    <div class="card-body">
        <div class="flex flex-wrap items-start justify-between gap-4">
            <div>
                <h5 class="text-xl font-bold mb-1">{{ $counseling->student->full_name }}</h5>
                <p class="text-neutral-500 dark:text-neutral-400 mb-0 text-sm">NIS: {{ $counseling->student->nis }} &bull; Tahun Ajaran: {{ $counseling->academicYear->name }}</p>
            </div>
            <div class="flex flex-wrap items-center gap-2">
                @php
                    $categoryColors = ['pribadi'=>'bg-purple-100 text-purple-600','sosial'=>'bg-blue-100 text-blue-600','belajar'=>'bg-amber-100 text-amber-600','karir'=>'bg-green-100 text-green-600'];
                    $statusColors = ['scheduled'=>'bg-sky-100 text-sky-600','ongoing'=>'bg-amber-100 text-amber-600','completed'=>'bg-success-100 text-success-600','followed_up'=>'bg-primary-100 text-primary-600'];
                    $statusLabels = ['scheduled'=>'Dijadwalkan','ongoing'=>'Berlangsung','completed'=>'Selesai','followed_up'=>'Tindak Lanjut'];
                @endphp
                <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $categoryColors[$counseling->category] ?? '' }}">{{ ucfirst($counseling->category) }}</span>
                <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $statusColors[$counseling->status] ?? '' }}">{{ $statusLabels[$counseling->status] ?? $counseling->status }}</span>
                <a href="{{ route('guru-bk.individual-counselings.pdf', $counseling) }}" target="_blank"
                    class="btn btn-outline-secondary !rounded-lg flex items-center gap-2 !py-1.5 !text-sm">
                    <iconify-icon icon="solar:printer-bold" class="text-base"></iconify-icon>
                    Cetak PDF
                </a>
                <a href="{{ route('guru-bk.individual-counselings.edit', $counseling) }}" class="btn btn-primary-600 !rounded-lg flex items-center gap-2 !py-1.5 !text-sm">
                    <iconify-icon icon="solar:pen-bold" class="text-base"></iconify-icon>
                    Edit
                </a>
                <form action="{{ route('guru-bk.individual-counselings.destroy', $counseling) }}" method="POST" onsubmit="return confirm('Hapus sesi konseling ini?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger !rounded-lg flex items-center gap-2 !py-1.5 !text-sm">
                        <iconify-icon icon="solar:trash-bin-trash-bold" class="text-base"></iconify-icon>
                        Hapus
                    </button>
                </form>
            </div>
        </div>
        <div class="mt-4 pt-4 border-t border-neutral-200 dark:border-neutral-700 flex flex-wrap gap-6 text-sm">
            <div class="flex items-center gap-2 text-neutral-500 dark:text-neutral-400">
                <iconify-icon icon="solar:calendar-bold" class="text-lg text-primary-600"></iconify-icon>
                <span>{{ $counseling->scheduled_at->format('d M Y, H:i') }}</span>
            </div>
            <div class="flex items-center gap-2 text-neutral-500 dark:text-neutral-400">
                <iconify-icon icon="solar:user-id-bold" class="text-lg text-primary-600"></iconify-icon>
                <span>Konselor: <strong class="text-neutral-700 dark:text-neutral-200">{{ $counseling->counselor->name }}</strong></span>
            </div>
        </div>
    </div>
</div>

{{-- Detail Content --}}
<div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
    <div class="card">
        <div class="card-header"><h6 class="font-semibold mb-0 flex items-center gap-2"><iconify-icon icon="solar:document-text-bold" class="text-primary-600"></iconify-icon>Deskripsi Masalah</h6></div>
        <div class="card-body"><p class="text-neutral-700 dark:text-neutral-300 whitespace-pre-wrap mb-0">{{ $counseling->problem_description }}</p></div>
    </div>
    <div class="card">
        <div class="card-header"><h6 class="font-semibold mb-0 flex items-center gap-2"><iconify-icon icon="solar:settings-bold" class="text-primary-600"></iconify-icon>Pendekatan / Teknik</h6></div>
        <div class="card-body">
            @if($counseling->approach)<p class="text-neutral-700 dark:text-neutral-300 whitespace-pre-wrap mb-0">{{ $counseling->approach }}</p>
            @else<p class="text-neutral-400 italic mb-0">Belum diisi.</p>@endif
        </div>
    </div>
    <div class="card">
        <div class="card-header"><h6 class="font-semibold mb-0 flex items-center gap-2"><iconify-icon icon="solar:check-circle-bold" class="text-success-600"></iconify-icon>Hasil Konseling</h6></div>
        <div class="card-body">
            @if($counseling->result)<p class="text-neutral-700 dark:text-neutral-300 whitespace-pre-wrap mb-0">{{ $counseling->result }}</p>
            @else<p class="text-neutral-400 italic mb-0">Belum diisi.</p>@endif
        </div>
    </div>
    <div class="card">
        <div class="card-header"><h6 class="font-semibold mb-0 flex items-center gap-2"><iconify-icon icon="solar:graph-bold" class="text-warning-600"></iconify-icon>Evaluasi</h6></div>
        <div class="card-body">
            @if($counseling->evaluation)<p class="text-neutral-700 dark:text-neutral-300 whitespace-pre-wrap mb-0">{{ $counseling->evaluation }}</p>
            @else<p class="text-neutral-400 italic mb-0">Belum diisi.</p>@endif
        </div>
    </div>
    <div class="card lg:col-span-2">
        <div class="card-header"><h6 class="font-semibold mb-0 flex items-center gap-2"><iconify-icon icon="solar:arrow-right-bold" class="text-primary-600"></iconify-icon>Tindak Lanjut</h6></div>
        <div class="card-body">
            @if($counseling->follow_up)<p class="text-neutral-700 dark:text-neutral-300 whitespace-pre-wrap mb-0">{{ $counseling->follow_up }}</p>
            @else<p class="text-neutral-400 italic mb-0">Belum diisi.</p>@endif
        </div>
    </div>
</div>

{{-- Dokumentasi Foto --}}
@if($counseling->documents->count() > 0)
<div class="card mt-4">
    <div class="card-header"><h6 class="font-semibold mb-0 flex items-center gap-2"><iconify-icon icon="solar:camera-bold" class="text-primary-600"></iconify-icon>Dokumentasi Foto</h6></div>
    <div class="card-body">
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3">
            @foreach($counseling->documents as $doc)
                <div class="relative group rounded-lg overflow-hidden border border-neutral-200 dark:border-neutral-700">
                    <img src="{{ Storage::url($doc->file_path) }}" alt="{{ $doc->file_name }}" class="w-full h-28 object-cover">
                    <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center gap-2">
                        <a href="{{ Storage::url($doc->file_path) }}" target="_blank" class="w-8 h-8 bg-white/20 hover:bg-white/40 rounded-lg flex items-center justify-center text-white">
                            <iconify-icon icon="solar:eye-bold" class="text-sm"></iconify-icon>
                        </a>
                        <form action="{{ route('guru-bk.individual-counselings.documents.destroy', [$counseling, $doc]) }}" method="POST" onsubmit="return confirm('Hapus foto ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="w-8 h-8 bg-danger-500/80 hover:bg-danger-600 rounded-lg flex items-center justify-center text-white">
                                <iconify-icon icon="solar:trash-bin-trash-bold" class="text-sm"></iconify-icon>
                            </button>
                        </form>
                    </div>
                    <p class="text-xs text-neutral-500 truncate px-2 py-1 bg-neutral-50 dark:bg-neutral-800">{{ $doc->file_name }}</p>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endif

<div class="mt-4">
    <a href="{{ route('guru-bk.individual-counselings.index') }}" class="btn btn-outline-secondary flex items-center gap-2 w-fit">
        <iconify-icon icon="solar:arrow-left-bold" class="text-lg"></iconify-icon>
        Kembali ke Daftar
    </a>
</div>
@endsection
