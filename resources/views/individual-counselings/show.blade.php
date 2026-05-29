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
                    $categoryColors = [
                        'pribadi' => 'bg-purple-100 text-purple-600 dark:bg-purple-900/30 dark:text-purple-400',
                        'sosial' => 'bg-blue-100 text-blue-600 dark:bg-blue-900/30 dark:text-blue-400',
                        'belajar' => 'bg-amber-100 text-amber-600 dark:bg-amber-900/30 dark:text-amber-400',
                        'karir' => 'bg-green-100 text-green-600 dark:bg-green-900/30 dark:text-green-400',
                    ];
                    $statusColors = [
                        'scheduled' => 'bg-sky-100 text-sky-600 dark:bg-sky-900/30 dark:text-sky-400',
                        'ongoing' => 'bg-amber-100 text-amber-600 dark:bg-amber-900/30 dark:text-amber-400',
                        'completed' => 'bg-success-100 text-success-600 dark:bg-success-600/25 dark:text-success-400',
                        'followed_up' => 'bg-primary-100 text-primary-600 dark:bg-primary-600/25 dark:text-primary-400',
                    ];
                    $statusLabels = [
                        'scheduled' => 'Dijadwalkan',
                        'ongoing' => 'Berlangsung',
                        'completed' => 'Selesai',
                        'followed_up' => 'Tindak Lanjut',
                    ];
                @endphp
                <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $categoryColors[$counseling->category] ?? '' }}">
                    {{ ucfirst($counseling->category) }}
                </span>
                <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $statusColors[$counseling->status] ?? '' }}">
                    {{ $statusLabels[$counseling->status] ?? $counseling->status }}
                </span>
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
            <div class="flex items-center gap-2 text-neutral-500 dark:text-neutral-400">
                <iconify-icon icon="solar:clock-circle-bold" class="text-lg text-primary-600"></iconify-icon>
                <span>Dibuat: {{ $counseling->created_at->format('d M Y') }}</span>
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
                Deskripsi Masalah
            </h6>
        </div>
        <div class="card-body">
            <p class="text-neutral-700 dark:text-neutral-300 whitespace-pre-wrap mb-0">{{ $counseling->problem_description }}</p>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h6 class="font-semibold mb-0 flex items-center gap-2">
                <iconify-icon icon="solar:settings-bold" class="text-primary-600"></iconify-icon>
                Pendekatan / Teknik Konseling
            </h6>
        </div>
        <div class="card-body">
            @if($counseling->approach)
                <p class="text-neutral-700 dark:text-neutral-300 whitespace-pre-wrap mb-0">{{ $counseling->approach }}</p>
            @else
                <p class="text-neutral-400 italic mb-0">Belum diisi.</p>
            @endif
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h6 class="font-semibold mb-0 flex items-center gap-2">
                <iconify-icon icon="solar:check-circle-bold" class="text-success-600"></iconify-icon>
                Hasil Konseling
            </h6>
        </div>
        <div class="card-body">
            @if($counseling->result)
                <p class="text-neutral-700 dark:text-neutral-300 whitespace-pre-wrap mb-0">{{ $counseling->result }}</p>
            @else
                <p class="text-neutral-400 italic mb-0">Belum diisi.</p>
            @endif
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h6 class="font-semibold mb-0 flex items-center gap-2">
                <iconify-icon icon="solar:arrow-right-bold" class="text-warning-600"></iconify-icon>
                Rencana Tindak Lanjut
            </h6>
        </div>
        <div class="card-body">
            @if($counseling->follow_up_plan)
                <p class="text-neutral-700 dark:text-neutral-300 whitespace-pre-wrap mb-0">{{ $counseling->follow_up_plan }}</p>
            @else
                <p class="text-neutral-400 italic mb-0">Belum diisi.</p>
            @endif
        </div>
    </div>
</div>

<div class="mt-4">
    <a href="{{ route('guru-bk.individual-counselings.index') }}" class="btn btn-outline-secondary flex items-center gap-2 w-fit">
        <iconify-icon icon="solar:arrow-left-bold" class="text-lg"></iconify-icon>
        Kembali ke Daftar
    </a>
</div>
@endsection
