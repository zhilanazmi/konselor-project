@extends('layouts.app')

@section('title', 'Detail Konseling Kelompok - KonselorKita')

@section('content')

@if(session('success'))
    <div class="bg-success-100 dark:bg-success-600/25 text-success-600 dark:text-success-400 px-4 py-3 rounded-lg mb-4 flex items-center gap-2">
        <iconify-icon icon="solar:check-circle-bold" class="text-xl"></iconify-icon>
        {{ session('success') }}
    </div>
@endif
@if(session('error'))
    <div class="bg-danger-100 dark:bg-danger-600/25 text-danger-600 dark:text-danger-400 px-4 py-3 rounded-lg mb-4 flex items-center gap-2">
        <iconify-icon icon="solar:danger-circle-bold" class="text-xl"></iconify-icon>
        {{ session('error') }}
    </div>
@endif

{{-- Header Card --}}
<div class="card mb-4">
    <div class="card-body">
        <div class="flex flex-wrap items-start justify-between gap-4">
            <div>
                <h5 class="text-xl font-bold mb-1">{{ $counseling->topic }}</h5>
                <p class="text-neutral-500 dark:text-neutral-400 mb-0 text-sm">Tahun Ajaran: {{ $counseling->academicYear->name }}</p>
            </div>
            <div class="flex flex-wrap items-center gap-2">
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
                <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $statusColors[$counseling->status] ?? '' }}">
                    {{ $statusLabels[$counseling->status] ?? $counseling->status }}
                </span>
                <a href="{{ route('guru-bk.group-counselings.edit', $counseling) }}" class="btn btn-primary-600 !rounded-lg flex items-center gap-2 !py-1.5 !text-sm">
                    <iconify-icon icon="solar:pen-bold" class="text-base"></iconify-icon>
                    Edit
                </a>
                <form action="{{ route('guru-bk.group-counselings.destroy', $counseling) }}" method="POST" onsubmit="return confirm('Hapus sesi konseling kelompok ini beserta semua data peserta?')">
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
                <span>{{ $counseling->scheduled_at?->format('d M Y, H:i') ?? 'Belum dijadwalkan' }}</span>
            </div>
            <div class="flex items-center gap-2 text-neutral-500 dark:text-neutral-400">
                <iconify-icon icon="solar:user-id-bold" class="text-lg text-primary-600"></iconify-icon>
                <span>Konselor: <strong class="text-neutral-700 dark:text-neutral-200">{{ $counseling->counselor->name }}</strong></span>
            </div>
            <div class="flex items-center gap-2 text-neutral-500 dark:text-neutral-400">
                <iconify-icon icon="solar:users-group-rounded-bold" class="text-lg text-primary-600"></iconify-icon>
                <span><strong class="text-neutral-700 dark:text-neutral-200">{{ $counseling->participants->count() }}</strong> Peserta</span>
            </div>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-4">

    {{-- Left: Info Detail --}}
    <div class="lg:col-span-2 flex flex-col gap-4">
        <div class="card">
            <div class="card-header">
                <h6 class="font-semibold mb-0 flex items-center gap-2">
                    <iconify-icon icon="solar:document-text-bold" class="text-primary-600"></iconify-icon>
                    Deskripsi / Tujuan
                </h6>
            </div>
            <div class="card-body">
                @if($counseling->description)
                    <p class="text-neutral-700 dark:text-neutral-300 whitespace-pre-wrap mb-0">{{ $counseling->description }}</p>
                @else
                    <p class="text-neutral-400 italic mb-0">Belum diisi.</p>
                @endif
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h6 class="font-semibold mb-0 flex items-center gap-2">
                    <iconify-icon icon="solar:settings-bold" class="text-primary-600"></iconify-icon>
                    Metode / Teknik
                </h6>
            </div>
            <div class="card-body">
                @if($counseling->method)
                    <p class="text-neutral-700 dark:text-neutral-300 whitespace-pre-wrap mb-0">{{ $counseling->method }}</p>
                @else
                    <p class="text-neutral-400 italic mb-0">Belum diisi.</p>
                @endif
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h6 class="font-semibold mb-0 flex items-center gap-2">
                    <iconify-icon icon="solar:check-circle-bold" class="text-success-600"></iconify-icon>
                    Hasil Kegiatan
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
                    <iconify-icon icon="solar:graph-bold" class="text-warning-600"></iconify-icon>
                    Evaluasi
                </h6>
            </div>
            <div class="card-body">
                @if($counseling->evaluation)
                    <p class="text-neutral-700 dark:text-neutral-300 whitespace-pre-wrap mb-0">{{ $counseling->evaluation }}</p>
                @else
                    <p class="text-neutral-400 italic mb-0">Belum diisi.</p>
                @endif
            </div>
        </div>
    </div>

    {{-- Right: Participants Management --}}
    <div class="flex flex-col gap-4">
        {{-- Add Participant --}}
        <div class="card">
            <div class="card-header">
                <h6 class="font-semibold mb-0 flex items-center gap-2">
                    <iconify-icon icon="solar:user-plus-bold" class="text-primary-600"></iconify-icon>
                    Tambah Peserta
                </h6>
            </div>
            <div class="card-body">
                <form action="{{ route('guru-bk.group-counselings.participants.store', $counseling) }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <select name="student_id" id="student_id" class="form-control @error('student_id') !border-danger-600 @enderror">
                            <option value="">-- Pilih Siswa --</option>
                            @foreach($allStudents as $student)
                                @unless($counseling->participants->contains($student->id))
                                    <option value="{{ $student->id }}">{{ $student->full_name }} ({{ $student->nis }})</option>
                                @endunless
                            @endforeach
                        </select>
                        @error('student_id')
                            <p class="text-danger-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-primary-600 w-full flex items-center justify-center gap-2 !rounded-lg">
                        <iconify-icon icon="ic:baseline-plus" class="text-lg"></iconify-icon>
                        Tambah
                    </button>
                </form>
            </div>
        </div>

        {{-- Participants List --}}
        <div class="card">
            <div class="card-header flex items-center justify-between">
                <h6 class="font-semibold mb-0 flex items-center gap-2">
                    <iconify-icon icon="solar:users-group-rounded-bold" class="text-primary-600"></iconify-icon>
                    Daftar Peserta
                </h6>
                <span class="text-xs font-semibold bg-primary-100 text-primary-600 dark:bg-primary-600/25 px-2 py-0.5 rounded-full">
                    {{ $counseling->participants->count() }}
                </span>
            </div>
            <div class="card-body !p-0">
                @forelse($counseling->participants as $participant)
                    <div class="border-b border-neutral-200 dark:border-neutral-700 last:border-b-0 p-3">
                        <div class="flex items-center justify-between mb-2">
                            <div>
                                <p class="font-medium text-sm mb-0">{{ $participant->full_name }}</p>
                                <p class="text-xs text-neutral-400 mb-0">{{ $participant->nis }}</p>
                            </div>
                            <form action="{{ route('guru-bk.group-counselings.participants.destroy', [$counseling, $participant]) }}" method="POST" onsubmit="return confirm('Hapus peserta ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-7 h-7 bg-danger-50 dark:bg-danger-600/25 text-danger-600 rounded-lg flex items-center justify-center hover:bg-danger-100" title="Hapus Peserta">
                                    <iconify-icon icon="solar:trash-bin-trash-bold" class="text-sm"></iconify-icon>
                                </button>
                            </form>
                        </div>
                        {{-- Notes per peserta --}}
                        <form action="{{ route('guru-bk.group-counselings.participants.update-notes', [$counseling, $participant]) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <div class="flex gap-2">
                                <input type="text" name="notes" value="{{ $participant->pivot->notes }}"
                                    placeholder="Catatan untuk peserta ini..."
                                    class="form-control !text-xs !py-1 !rounded-md flex-1">
                                <button type="submit" class="w-8 h-8 bg-success-50 dark:bg-success-600/25 text-success-600 rounded-lg flex items-center justify-center hover:bg-success-100 flex-shrink-0" title="Simpan Catatan">
                                    <iconify-icon icon="solar:diskette-bold" class="text-sm"></iconify-icon>
                                </button>
                            </div>
                        </form>
                    </div>
                @empty
                    <div class="p-4 text-center text-neutral-400">
                        <iconify-icon icon="solar:user-plus-bold" class="text-3xl mb-1"></iconify-icon>
                        <p class="text-sm mb-0">Belum ada peserta.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<div class="mt-4">
    <a href="{{ route('guru-bk.group-counselings.index') }}" class="btn btn-outline-secondary flex items-center gap-2 w-fit">
        <iconify-icon icon="solar:arrow-left-bold" class="text-lg"></iconify-icon>
        Kembali ke Daftar
    </a>
</div>
@endsection
