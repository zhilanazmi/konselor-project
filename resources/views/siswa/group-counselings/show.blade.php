@extends('layouts.app')

@section('title', 'Detail Konseling Kelompok - KonselorKita')

@section('content')

{{-- Header Card --}}
<div class="card mb-4">
    <div class="card-body">
        <div class="flex flex-wrap items-start justify-between gap-4">
            <div>
                <h5 class="text-xl font-bold mb-1">{{ $groupCounseling->topic }}</h5>
                <p class="text-neutral-500 dark:text-neutral-400 mb-0 text-sm">Tahun Ajaran: {{ $groupCounseling->academicYear->name }}</p>
            </div>
            <div>
                @php
                    $statusColors = [
                        'scheduled' => 'bg-sky-100 text-sky-600 dark:bg-sky-900/30 dark:text-sky-400',
                        'ongoing' => 'bg-amber-100 text-amber-600 dark:bg-amber-900/30 dark:text-amber-400',
                        'completed' => 'bg-success-100 text-success-600 dark:bg-success-600/25 dark:text-success-400',
                    ];
                    $statusLabels = [
                        'scheduled' => 'Dijadwalkan',
                        'ongoing' => 'Berlangsung',
                        'completed' => 'Selesai',
                    ];
                @endphp
                <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $statusColors[$groupCounseling->status] ?? '' }}">
                    {{ $statusLabels[$groupCounseling->status] ?? $groupCounseling->status }}
                </span>
            </div>
        </div>

        <div class="mt-4 pt-4 border-t border-neutral-200 dark:border-neutral-700 flex flex-wrap gap-6 text-sm">
            <div class="flex items-center gap-2 text-neutral-500 dark:text-neutral-400">
                <iconify-icon icon="solar:calendar-bold" class="text-lg text-primary-600"></iconify-icon>
                <span>{{ $groupCounseling->scheduled_at->format('d M Y, H:i') }}</span>
            </div>
            <div class="flex items-center gap-2 text-neutral-500 dark:text-neutral-400">
                <iconify-icon icon="solar:user-id-bold" class="text-lg text-primary-600"></iconify-icon>
                <span>Konselor: <strong class="text-neutral-700 dark:text-neutral-200">{{ $groupCounseling->counselor->name }}</strong></span>
            </div>
            <div class="flex items-center gap-2 text-neutral-500 dark:text-neutral-400">
                <iconify-icon icon="solar:users-group-rounded-bold" class="text-lg text-primary-600"></iconify-icon>
                <span>Metode: <strong class="text-neutral-700 dark:text-neutral-200">{{ ucfirst($groupCounseling->method ?? '-') }}</strong></span>
            </div>
        </div>
    </div>
</div>

{{-- Detail Content --}}
<div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
    <div class="card">
        <div class="card-header">
            <h6 class="font-semibold mb-0 flex items-center gap-2">
                <iconify-icon icon="solar:document-text-bold" class="text-primary-600"></iconify-icon>
                Deskripsi
            </h6>
        </div>
        <div class="card-body">
            @if($groupCounseling->description)
                <p class="text-neutral-700 dark:text-neutral-300 whitespace-pre-wrap mb-0">{{ $groupCounseling->description }}</p>
            @else
                <p class="text-neutral-400 italic mb-0">Belum diisi.</p>
            @endif
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h6 class="font-semibold mb-0 flex items-center gap-2">
                <iconify-icon icon="solar:check-circle-bold" class="text-success-600"></iconify-icon>
                Hasil
            </h6>
        </div>
        <div class="card-body">
            @if($groupCounseling->result)
                <p class="text-neutral-700 dark:text-neutral-300 whitespace-pre-wrap mb-0">{{ $groupCounseling->result }}</p>
            @else
                <p class="text-neutral-400 italic mb-0">Belum diisi.</p>
            @endif
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h6 class="font-semibold mb-0 flex items-center gap-2">
                <iconify-icon icon="solar:clipboard-list-bold" class="text-warning-600"></iconify-icon>
                Evaluasi
            </h6>
        </div>
        <div class="card-body">
            @if($groupCounseling->evaluation)
                <p class="text-neutral-700 dark:text-neutral-300 whitespace-pre-wrap mb-0">{{ $groupCounseling->evaluation }}</p>
            @else
                <p class="text-neutral-400 italic mb-0">Belum diisi.</p>
            @endif
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h6 class="font-semibold mb-0 flex items-center gap-2">
                <iconify-icon icon="solar:notebook-bold" class="text-info-600"></iconify-icon>
                Catatan Khusus untuk Anda
            </h6>
        </div>
        <div class="card-body">
            @if($myNotes)
                <p class="text-neutral-700 dark:text-neutral-300 whitespace-pre-wrap mb-0">{{ $myNotes }}</p>
            @else
                <p class="text-neutral-400 italic mb-0">Tidak ada catatan khusus.</p>
            @endif
        </div>
    </div>
</div>

{{-- Participants --}}
<div class="card mt-4">
    <div class="card-header">
        <h6 class="font-semibold mb-0 flex items-center gap-2">
            <iconify-icon icon="solar:users-group-rounded-bold" class="text-primary-600"></iconify-icon>
            Daftar Partisipan ({{ $groupCounseling->participants->count() }})
        </h6>
    </div>
    <div class="card-body">
        <div class="flex flex-wrap gap-2">
            @foreach($groupCounseling->participants as $participant)
                <span class="px-3 py-1.5 rounded-lg text-sm font-medium bg-neutral-100 dark:bg-neutral-700 text-neutral-700 dark:text-neutral-300">
                    {{ $participant->full_name }}
                </span>
            @endforeach
        </div>
    </div>
</div>

<div class="mt-4">
    <a href="{{ route('siswa.group-counselings.index') }}" class="btn btn-outline-secondary flex items-center gap-2 w-fit">
        <iconify-icon icon="solar:arrow-left-bold" class="text-lg"></iconify-icon>
        Kembali ke Daftar
    </a>
</div>
@endsection
