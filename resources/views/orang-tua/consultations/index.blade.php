@extends('layouts.app')

@section('title', 'Riwayat Konsultasi Orang Tua - KonselorKita')

@section('content')

<div class="card">
    <div class="card-header flex flex-wrap items-center justify-between gap-4">
        <div>
            <h6 class="text-lg font-semibold mb-1">Riwayat Konsultasi Orang Tua</h6>
            <p class="text-sm text-neutral-500 dark:text-neutral-400 mb-0">Daftar sesi konsultasi Anda dengan Guru BK terkait anak.</p>
        </div>
    </div>

    <div class="card-body">
        <div class="overflow-x-auto">
            <table class="table bordered-table mb-0">
                <thead>
                    <tr>
                        <th scope="col" class="!text-center w-12">No</th>
                        <th scope="col">Siswa</th>
                        <th scope="col">Konselor</th>
                        <th scope="col">Topik</th>
                        <th scope="col" class="!text-center">Status</th>
                        <th scope="col">Jadwal</th>
                        <th scope="col" class="!text-center w-20">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($consultations as $index => $consultation)
                        <tr>
                            <td class="!text-center">{{ $consultations->firstItem() + $index }}</td>
                            <td><span class="font-medium">{{ $consultation->student->full_name }}</span></td>
                            <td>{{ $consultation->counselor->name }}</td>
                            <td class="max-w-xs truncate">{{ $consultation->topic }}</td>
                            <td class="!text-center">
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
                                <span class="px-2 py-0.5 rounded text-xs font-medium {{ $statusColors[$consultation->status] ?? '' }}">
                                    {{ $statusLabels[$consultation->status] ?? $consultation->status }}
                                </span>
                            </td>
                            <td class="text-sm text-neutral-500 dark:text-neutral-400">
                                {{ $consultation->scheduled_at->format('d M Y, H:i') }}
                            </td>
                            <td class="!text-center">
                                <a href="{{ route('orang-tua.consultations.show', $consultation) }}" class="w-8 h-8 bg-info-50 dark:bg-info-600/25 text-info-600 rounded-lg flex items-center justify-center hover:bg-info-100 mx-auto" title="Detail">
                                    <iconify-icon icon="solar:eye-bold" class="text-lg"></iconify-icon>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="!text-center py-8">
                                <div class="flex flex-col items-center gap-2 text-neutral-400">
                                    <iconify-icon icon="solar:folder-open-bold" class="text-4xl"></iconify-icon>
                                    <p class="mb-0">Belum ada riwayat konsultasi orang tua.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($consultations->hasPages())
            <div class="mt-4">
                {{ $consultations->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
