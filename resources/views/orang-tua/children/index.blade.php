@extends('layouts.app')

@section('title', 'Informasi Anak - KonselorKita')

@section('content')

<div class="card mb-4">
    <div class="card-header">
        <h6 class="text-lg font-semibold mb-0">Informasi Anak Saya</h6>
    </div>
    <div class="card-body">
        <p class="text-sm text-neutral-500 dark:text-neutral-400 mb-4">Daftar anak yang terhubung dengan akun Anda beserta informasi konseling mereka.</p>

        @if($children->isEmpty())
            <div class="flex flex-col items-center gap-2 text-neutral-400 py-8">
                <iconify-icon icon="solar:users-group-rounded-bold" class="text-4xl"></iconify-icon>
                <p class="mb-0">Belum ada data anak yang terhubung dengan akun Anda.</p>
            </div>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-4">
                @foreach($children as $child)
                    <div class="card border border-neutral-200 dark:border-neutral-700">
                        <div class="card-body">
                            <div class="flex items-start gap-3 mb-3">
                                <div class="w-12 h-12 rounded-full bg-primary-100 dark:bg-primary-900/30 flex items-center justify-center text-primary-600 text-lg font-bold flex-shrink-0">
                                    {{ strtoupper(substr($child->full_name, 0, 1)) }}
                                </div>
                                <div>
                                    <h6 class="font-semibold mb-0.5">{{ $child->full_name }}</h6>
                                    <p class="text-xs text-neutral-500 dark:text-neutral-400 mb-0">NIS: {{ $child->nis }}</p>
                                </div>
                            </div>

                            @php
                                $activeClassroom = $child->classrooms
                                    ->sortByDesc(fn ($c) => $c->academicYear->is_active ?? false)
                                    ->first();
                            @endphp

                            <div class="flex flex-col gap-1.5 text-sm mb-4">
                                <div class="flex items-center gap-2 text-neutral-600 dark:text-neutral-400">
                                    <iconify-icon icon="solar:buildings-bold" class="text-base text-primary-500"></iconify-icon>
                                    <span>Kelas: <strong>{{ $activeClassroom?->name ?? 'Belum ditetapkan' }}</strong></span>
                                </div>
                                <div class="flex items-center gap-2 text-neutral-600 dark:text-neutral-400">
                                    <iconify-icon icon="solar:chat-round-dots-bold" class="text-base text-primary-500"></iconify-icon>
                                    <span>Sesi Konseling: <strong>{{ $child->individual_counselings_count }}</strong></span>
                                </div>
                                <div class="flex items-center gap-2 text-neutral-600 dark:text-neutral-400">
                                    <iconify-icon icon="solar:user-speak-bold" class="text-base text-primary-500"></iconify-icon>
                                    <span>Hubungan: <strong>{{ ucfirst($child->pivot->relationship ?? '-') }}</strong></span>
                                </div>
                            </div>

                            <a href="{{ route('orang-tua.children.counselings', $child) }}" class="btn btn-primary-600 w-full !rounded-lg flex items-center justify-center gap-2 !text-sm">
                                <iconify-icon icon="solar:eye-bold"></iconify-icon>
                                Lihat Riwayat Konseling
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection
