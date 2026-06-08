@extends('layouts.app')

@section('title', 'Detail Jurnal Kegiatan - KonselorKita')

@section('content')

@if(session('success'))
    <div class="bg-success-100 dark:bg-success-600/25 text-success-600 dark:text-success-400 px-4 py-3 rounded-lg mb-4 flex items-center gap-2">
        <iconify-icon icon="solar:check-circle-bold" class="text-xl"></iconify-icon>
        {{ session('success') }}
    </div>
@endif

@php
    $typeColors = [
        'layanan_dasar' => 'bg-blue-100 text-blue-600 dark:bg-blue-900/30 dark:text-blue-400',
        'layanan_responsif' => 'bg-amber-100 text-amber-600 dark:bg-amber-900/30 dark:text-amber-400',
        'layanan_perencanaan' => 'bg-purple-100 text-purple-600 dark:bg-purple-900/30 dark:text-purple-400',
        'dukungan_sistem' => 'bg-green-100 text-green-600 dark:bg-green-900/30 dark:text-green-400',
    ];
@endphp

{{-- Header Card --}}
<div class="card mb-4">
    <div class="card-body">
        <div class="flex flex-wrap items-start justify-between gap-4">
            <div>
                <h5 class="text-xl font-bold mb-1">{{ $journal->title }}</h5>
                <p class="text-neutral-500 dark:text-neutral-400 mb-0 text-sm">
                    Tahun Ajaran: {{ $journal->academicYear->name }}
                </p>
            </div>
            <div class="flex flex-wrap items-center gap-2">
                <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $typeColors[$journal->activity_type] ?? '' }}">
                    {{ $activityTypeLabels[$journal->activity_type] ?? $journal->activity_type }}
                </span>
                <a href="{{ route('guru-bk.guru-bk-journals.edit', $journal) }}" class="btn btn-primary-600 !rounded-lg flex items-center gap-2 !py-1.5 !text-sm">
                    <iconify-icon icon="solar:pen-bold" class="text-base"></iconify-icon>
                    Edit
                </a>
                <form action="{{ route('guru-bk.guru-bk-journals.destroy', $journal) }}" method="POST" onsubmit="return confirm('Hapus jurnal kegiatan ini?')">
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
                <span>{{ $journal->date->translatedFormat('d F Y') }}</span>
            </div>
            @if($journal->location)
            <div class="flex items-center gap-2 text-neutral-500 dark:text-neutral-400">
                <iconify-icon icon="solar:map-point-bold" class="text-lg text-primary-600"></iconify-icon>
                <span>{{ $journal->location }}</span>
            </div>
            @endif
            @if($journal->target_group)
            <div class="flex items-center gap-2 text-neutral-500 dark:text-neutral-400">
                <iconify-icon icon="solar:users-group-rounded-bold" class="text-lg text-primary-600"></iconify-icon>
                <span>Sasaran: <strong class="text-neutral-700 dark:text-neutral-200">{{ $journal->target_group }}</strong></span>
            </div>
            @endif
            @if($journal->duration_minutes)
            <div class="flex items-center gap-2 text-neutral-500 dark:text-neutral-400">
                <iconify-icon icon="solar:clock-circle-bold" class="text-lg text-primary-600"></iconify-icon>
                <span>{{ $journal->duration_minutes }} menit</span>
            </div>
            @endif
            <div class="flex items-center gap-2 text-neutral-500 dark:text-neutral-400">
                <iconify-icon icon="solar:user-id-bold" class="text-lg text-primary-600"></iconify-icon>
                <span>Konselor: <strong class="text-neutral-700 dark:text-neutral-200">{{ $journal->counselor->name }}</strong></span>
            </div>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
    <div class="card">
        <div class="card-header">
            <h6 class="font-semibold mb-0 flex items-center gap-2">
                <iconify-icon icon="solar:document-text-bold" class="text-primary-600"></iconify-icon>
                Deskripsi Kegiatan
            </h6>
        </div>
        <div class="card-body">
            @if($journal->description)
                <p class="text-neutral-700 dark:text-neutral-300 whitespace-pre-wrap mb-0">{{ $journal->description }}</p>
            @else
                <p class="text-neutral-400 italic mb-0">Belum diisi.</p>
            @endif
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h6 class="font-semibold mb-0 flex items-center gap-2">
                <iconify-icon icon="solar:notes-bold" class="text-warning-600"></iconify-icon>
                Catatan Tambahan
            </h6>
        </div>
        <div class="card-body">
            @if($journal->notes)
                <p class="text-neutral-700 dark:text-neutral-300 whitespace-pre-wrap mb-0">{{ $journal->notes }}</p>
            @else
                <p class="text-neutral-400 italic mb-0">Tidak ada catatan.</p>
            @endif
        </div>
    </div>
</div>

<div class="mt-4">
    <a href="{{ route('guru-bk.guru-bk-journals.index') }}" class="btn btn-outline-secondary flex items-center gap-2 w-fit">
        <iconify-icon icon="solar:arrow-left-bold" class="text-lg"></iconify-icon>
        Kembali ke Daftar
    </a>
</div>

@endsection
