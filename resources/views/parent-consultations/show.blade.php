@extends('layouts.app')

@section('title', 'Detail Konsultasi Orang Tua - KonselorKita')

@section('content')

@if(session('success'))
    <div class="bg-success-100 dark:bg-success-600/25 text-success-600 dark:text-success-400 px-4 py-3 rounded-lg mb-4 flex items-center gap-2">
        <iconify-icon icon="solar:check-circle-bold" class="text-xl"></iconify-icon>
        {{ session('success') }}
    </div>
@endif

<div class="card mb-4">
    <div class="card-body">
        <div class="flex flex-wrap items-start justify-between gap-4">
            <div>
                <div class="flex items-center gap-3 mb-1">
                    <h5 class="text-xl font-bold mb-0">Konsultasi: {{ $consultation->guardian->full_name }}</h5>
                    @if($consultation->status == 'requested')
                        <span class="px-3 py-1 rounded-full text-xs font-semibold bg-warning-100 text-warning-600 border border-warning-200">Diminta</span>
                    @elseif($consultation->status == 'scheduled')
                        <span class="px-3 py-1 rounded-full text-xs font-semibold bg-info-100 text-info-600 border border-info-200">Dijadwalkan</span>
                    @else
                        <span class="px-3 py-1 rounded-full text-xs font-semibold bg-success-100 text-success-600 border border-success-200">Selesai</span>
                    @endif
                </div>
                <p class="text-neutral-500 dark:text-neutral-400 mb-0 text-sm">
                    Mengenai siswa: <strong class="text-neutral-700 dark:text-neutral-200">{{ $consultation->student->full_name }}</strong>
                    ({{ $consultation->student->nis }}) &bull; Tahun Ajaran: {{ $consultation->academicYear->name }}
                </p>
            </div>
            <div class="flex flex-wrap items-center gap-2">
                <a href="{{ route('guru-bk.parent-consultations.edit', $consultation) }}" class="btn btn-primary-600 !rounded-lg flex items-center gap-2 !py-1.5 !text-sm">
                    <iconify-icon icon="solar:pen-bold" class="text-base"></iconify-icon>
                    Edit
                </a>
                <form action="{{ route('guru-bk.parent-consultations.destroy', $consultation) }}" method="POST" onsubmit="return confirm('Hapus data konsultasi ini?')">
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
                <iconify-icon icon="solar:clock-circle-bold" class="text-lg text-primary-600"></iconify-icon>
                <span>Jadwal: <strong class="text-neutral-700 dark:text-neutral-200">{{ $consultation->scheduled_at->format('d M Y, H:i') }}</strong></span>
            </div>
            <div class="flex items-center gap-2 text-neutral-500 dark:text-neutral-400">
                <iconify-icon icon="solar:hand-stars-bold" class="text-lg text-primary-600"></iconify-icon>
                <span>Pemohon:
                    <strong class="text-neutral-700 dark:text-neutral-200">
                        {{ $consultation->requested_by == 'guru_bk' ? 'Guru BK' : 'Orang Tua / Wali' }}
                    </strong>
                </span>
            </div>
            <div class="flex items-center gap-2 text-neutral-500 dark:text-neutral-400">
                <iconify-icon icon="solar:user-id-bold" class="text-lg text-primary-600"></iconify-icon>
                <span>Konselor BK: <strong class="text-neutral-700 dark:text-neutral-200">{{ $consultation->counselor->name }}</strong></span>
            </div>
        </div>
    </div>
</div>

<div class="card mb-4">
    <div class="card-header">
        <h6 class="font-semibold mb-0 flex items-center gap-2">
            <iconify-icon icon="solar:chat-round-line-bold" class="text-primary-600"></iconify-icon>
            Topik Pembahasan
        </h6>
    </div>
    <div class="card-body">
        <p class="text-neutral-700 dark:text-neutral-300 whitespace-pre-wrap mb-0">{{ $consultation->topic }}</p>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
    <div class="card">
        <div class="card-header">
            <h6 class="font-semibold mb-0 flex items-center gap-2">
                <iconify-icon icon="solar:document-add-bold" class="text-info-600"></iconify-icon>
                Hasil Konsultasi
            </h6>
        </div>
        <div class="card-body">
            @if($consultation->result)
                <p class="text-neutral-700 dark:text-neutral-300 whitespace-pre-wrap mb-0">{{ $consultation->result }}</p>
            @else
                <p class="text-neutral-400 italic mb-0">Belum ada hasil yang dicatat.</p>
            @endif
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h6 class="font-semibold mb-0 flex items-center gap-2">
                <iconify-icon icon="solar:handshake-bold" class="text-success-600"></iconify-icon>
                Kesepakatan / Tindak Lanjut
            </h6>
        </div>
        <div class="card-body">
            @if($consultation->agreement)
                <p class="text-neutral-700 dark:text-neutral-300 whitespace-pre-wrap mb-0">{{ $consultation->agreement }}</p>
            @else
                <p class="text-neutral-400 italic mb-0">Belum ada kesepakatan.</p>
            @endif
        </div>
    </div>
</div>

<div class="mt-4">
    <a href="{{ route('guru-bk.parent-consultations.index') }}" class="btn btn-outline-secondary flex items-center gap-2 w-fit">
        <iconify-icon icon="solar:arrow-left-bold" class="text-lg"></iconify-icon>
        Kembali ke Daftar
    </a>
</div>
@endsection
