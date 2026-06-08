@extends('layouts.app')

@section('title', 'Konsultasi Guru Mapel - KonselorKita')

@section('content')

<div class="card">
    <div class="card-header flex flex-wrap items-center justify-between gap-4">
        <div>
            <h6 class="text-lg font-semibold mb-1">Riwayat Konsultasi Guru Mata Pelajaran</h6>
            <p class="text-sm text-neutral-500 dark:text-neutral-400 mb-0">Daftar sesi konsultasi Anda dengan Guru BK terkait kendala belajar siswa.</p>
        </div>
    </div>

    <div class="card-body">
        <div class="overflow-x-auto">
            <table class="table bordered-table mb-0">
                <thead>
                    <tr>
                        <th scope="col" class="!text-center w-12">No</th>
                        <th scope="col">Siswa</th>
                        <th scope="col">Mata Pelajaran</th>
                        <th scope="col">Konselor</th>
                        <th scope="col">Topik</th>
                        <th scope="col">Tanggal</th>
                        <th scope="col" class="!text-center w-20">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($consultations as $index => $consultation)
                        <tr>
                            <td class="!text-center">{{ $consultations->firstItem() + $index }}</td>
                            <td><span class="font-medium">{{ $consultation->student->full_name }}</span></td>
                            <td>
                                <span class="px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-600 dark:bg-blue-900/30 dark:text-blue-400">
                                    {{ $consultation->subject_name }}
                                </span>
                            </td>
                            <td>{{ $consultation->counselor->name }}</td>
                            <td class="max-w-xs truncate">{{ $consultation->topic }}</td>
                            <td class="text-sm text-neutral-500 dark:text-neutral-400">
                                {{ $consultation->consultation_date->format('d M Y, H:i') }}
                            </td>
                            <td class="!text-center">
                                <a href="{{ route('guru.subject-consultations.show', $consultation) }}" class="w-8 h-8 bg-info-50 dark:bg-info-600/25 text-info-600 rounded-lg flex items-center justify-center hover:bg-info-100 mx-auto" title="Detail">
                                    <iconify-icon icon="solar:eye-bold" class="text-lg"></iconify-icon>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="!text-center py-8">
                                <div class="flex flex-col items-center gap-2 text-neutral-400">
                                    <iconify-icon icon="solar:folder-open-bold" class="text-4xl"></iconify-icon>
                                    <p class="mb-0">Belum ada riwayat konsultasi guru mapel.</p>
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
