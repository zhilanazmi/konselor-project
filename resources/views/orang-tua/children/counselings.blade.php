@extends('layouts.app')

@section('title', 'Riwayat Konseling Anak - KonselorKita')

@section('content')

<div class="card">
    <div class="card-header flex flex-wrap items-center justify-between gap-4">
        <div>
            <h6 class="text-lg font-semibold mb-1">Riwayat Konseling — {{ $student->full_name }}</h6>
            <p class="text-sm text-neutral-500 dark:text-neutral-400 mb-0">NIS: {{ $student->nis }}</p>
        </div>
        <a href="{{ route('orang-tua.children.index') }}" class="btn btn-outline-secondary !rounded-lg flex items-center gap-2 !text-sm">
            <iconify-icon icon="solar:arrow-left-bold"></iconify-icon>
            Kembali
        </a>
    </div>

    <div class="card-body">
        <div class="overflow-x-auto">
            <table class="table bordered-table mb-0">
                <thead>
                    <tr>
                        <th scope="col" class="!text-center w-12">No</th>
                        <th scope="col">Konselor</th>
                        <th scope="col">Tahun Ajaran</th>
                        <th scope="col" class="!text-center">Kategori</th>
                        <th scope="col" class="!text-center">Status</th>
                        <th scope="col">Jadwal</th>
                        <th scope="col" class="!text-center w-20">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($counselings as $index => $counseling)
                        <tr>
                            <td class="!text-center">{{ $counselings->firstItem() + $index }}</td>
                            <td><span class="font-medium">{{ $counseling->counselor->name }}</span></td>
                            <td>{{ $counseling->academicYear->name }}</td>
                            <td class="!text-center">
                                @php
                                    $categoryColors = [
                                        'pribadi' => 'bg-purple-100 text-purple-600 dark:bg-purple-900/30 dark:text-purple-400',
                                        'sosial' => 'bg-blue-100 text-blue-600 dark:bg-blue-900/30 dark:text-blue-400',
                                        'belajar' => 'bg-amber-100 text-amber-600 dark:bg-amber-900/30 dark:text-amber-400',
                                        'karir' => 'bg-green-100 text-green-600 dark:bg-green-900/30 dark:text-green-400',
                                    ];
                                @endphp
                                <span class="px-2 py-0.5 rounded text-xs font-medium {{ $categoryColors[$counseling->category] ?? '' }}">
                                    {{ ucfirst($counseling->category) }}
                                </span>
                            </td>
                            <td class="!text-center">
                                @php
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
                                <span class="px-2 py-0.5 rounded text-xs font-medium {{ $statusColors[$counseling->status] ?? '' }}">
                                    {{ $statusLabels[$counseling->status] ?? $counseling->status }}
                                </span>
                            </td>
                            <td class="text-sm text-neutral-500 dark:text-neutral-400">
                                {{ $counseling->scheduled_at->format('d M Y, H:i') }}
                            </td>
                            <td class="!text-center">
                                <a href="{{ route('orang-tua.children.counseling-show', [$student, $counseling]) }}" class="w-8 h-8 bg-info-50 dark:bg-info-600/25 text-info-600 rounded-lg flex items-center justify-center hover:bg-info-100 mx-auto" title="Detail">
                                    <iconify-icon icon="solar:eye-bold" class="text-lg"></iconify-icon>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="!text-center py-8">
                                <div class="flex flex-col items-center gap-2 text-neutral-400">
                                    <iconify-icon icon="solar:folder-open-bold" class="text-4xl"></iconify-icon>
                                    <p class="mb-0">Belum ada riwayat konseling untuk anak ini.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($counselings->hasPages())
            <div class="mt-4">
                {{ $counselings->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
