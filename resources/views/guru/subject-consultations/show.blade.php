@extends('layouts.app')

@section('title', 'Detail Konsultasi Guru Mapel - KonselorKita')

@section('content')

{{-- Header Card --}}
<div class="card mb-4">
    <div class="card-body">
        <div class="flex flex-wrap items-start justify-between gap-4">
            <div>
                <h5 class="text-xl font-bold mb-1">Detail Konsultasi Guru Mapel</h5>
                <p class="text-neutral-500 dark:text-neutral-400 mb-0 text-sm">Siswa: {{ $consultation->student->full_name }} &bull; Tahun Ajaran: {{ $consultation->academicYear->name }}</p>
            </div>
            <div>
                <span class="px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-600 dark:bg-blue-900/30 dark:text-blue-400">
                    {{ $consultation->subject_name }}
                </span>
            </div>
        </div>

        <div class="mt-4 pt-4 border-t border-neutral-200 dark:border-neutral-700 flex flex-wrap gap-6 text-sm">
            <div class="flex items-center gap-2 text-neutral-500 dark:text-neutral-400">
                <iconify-icon icon="solar:calendar-bold" class="text-lg text-primary-600"></iconify-icon>
                <span>{{ $consultation->consultation_date->format('d M Y, H:i') }}</span>
            </div>
            <div class="flex items-center gap-2 text-neutral-500 dark:text-neutral-400">
                <iconify-icon icon="solar:user-id-bold" class="text-lg text-primary-600"></iconify-icon>
                <span>Konselor: <strong class="text-neutral-700 dark:text-neutral-200">{{ $consultation->counselor->name }}</strong></span>
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
                Topik Konsultasi
            </h6>
        </div>
        <div class="card-body">
            @if($consultation->topic)
                <p class="text-neutral-700 dark:text-neutral-300 whitespace-pre-wrap mb-0">{{ $consultation->topic }}</p>
            @else
                <p class="text-neutral-400 italic mb-0">Belum diisi.</p>
            @endif
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h6 class="font-semibold mb-0 flex items-center gap-2">
                <iconify-icon icon="solar:clipboard-list-bold" class="text-warning-600"></iconify-icon>
                Rekomendasi
            </h6>
        </div>
        <div class="card-body">
            @if($consultation->recommendation)
                <p class="text-neutral-700 dark:text-neutral-300 whitespace-pre-wrap mb-0">{{ $consultation->recommendation }}</p>
            @else
                <p class="text-neutral-400 italic mb-0">Belum diisi.</p>
            @endif
        </div>
    </div>

    <div class="card lg:col-span-2">
        <div class="card-header">
            <h6 class="font-semibold mb-0 flex items-center gap-2">
                <iconify-icon icon="solar:arrow-right-bold" class="text-success-600"></iconify-icon>
                Tindak Lanjut
            </h6>
        </div>
        <div class="card-body">
            @if($consultation->follow_up)
                <p class="text-neutral-700 dark:text-neutral-300 whitespace-pre-wrap mb-0">{{ $consultation->follow_up }}</p>
            @else
                <p class="text-neutral-400 italic mb-0">Belum diisi.</p>
            @endif
        </div>
    </div>
</div>

<div class="mt-4">
    <a href="{{ route('guru.subject-consultations.index') }}" class="btn btn-outline-secondary flex items-center gap-2 w-fit">
        <iconify-icon icon="solar:arrow-left-bold" class="text-lg"></iconify-icon>
        Kembali ke Daftar
    </a>
</div>
@endsection
