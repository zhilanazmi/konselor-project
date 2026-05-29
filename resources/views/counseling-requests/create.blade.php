@extends('layouts.app')

@section('title', $pageTitle . ' - KonselorKita')

@section('content')
<div class="card max-w-2xl mx-auto">
    <div class="card-header flex items-center justify-between">
        <h6 class="text-lg font-semibold mb-0">{{ $pageTitle }}</h6>
        @php
            $backRoute = auth()->user()->isSiswa() 
                ? route('siswa.counseling-requests.index') 
                : route('orang-tua.counseling-requests.index');
        @endphp
        <a href="{{ $backRoute }}" class="btn btn-sm btn-outline-secondary !rounded-lg flex items-center gap-1">
            <iconify-icon icon="solar:arrow-left-bold" class="text-lg"></iconify-icon>
            Kembali
        </a>
    </div>

    <div class="card-body">
        @php
            $storeRoute = auth()->user()->isSiswa() 
                ? route('siswa.counseling-requests.store') 
                : route('orang-tua.counseling-requests.store');
        @endphp
        
        <form action="{{ $storeRoute }}" method="POST" class="flex flex-col gap-5">
            @csrf

            {{-- Student Field --}}
            @if(auth()->user()->isSiswa())
                <div>
                    <label class="form-label font-medium text-neutral-700 dark:text-neutral-300">Nama Siswa</label>
                    <input type="text" class="form-control bg-neutral-50 dark:bg-neutral-800 cursor-not-allowed !rounded-lg" value="{{ $student->full_name }} ({{ $student->nis }})" disabled>
                    <input type="hidden" name="student_id" value="{{ $student->id }}">
                </div>
            @elseif(auth()->user()->isOrangTua())
                <div>
                    <label for="student_id" class="form-label font-medium text-neutral-700 dark:text-neutral-300">Pilih Anak / Wali</label>
                    <select name="student_id" id="student_id" class="form-control !rounded-lg @error('student_id') is-invalid @enderror" required>
                        <option value="">-- Pilih Anak --</option>
                        @foreach($students as $item)
                            <option value="{{ $item->id }}" {{ old('student_id') == $item->id ? 'selected' : '' }}>
                                {{ $item->full_name }} (NIS: {{ $item->nis }})
                            </option>
                        @endforeach
                    </select>
                    @error('student_id')
                        <div class="text-danger-600 text-xs mt-1">{{ $message }}</div>
                    @enderror
                </div>
            @endif

            {{-- Counselor Field --}}
            <div>
                <label for="counselor_id" class="form-label font-medium text-neutral-700 dark:text-neutral-300">Pilih Guru Bimbingan Konseling (BK)</label>
                <select name="counselor_id" id="counselor_id" class="form-control !rounded-lg @error('counselor_id') is-invalid @enderror" required>
                    <option value="">-- Pilih Guru BK --</option>
                    @foreach($counselors as $counselor)
                        <option value="{{ $counselor->id }}" {{ old('counselor_id') == $counselor->id ? 'selected' : '' }}>
                            {{ $counselor->name }}
                        </option>
                    @endforeach
                </select>
                @error('counselor_id')
                    <div class="text-danger-600 text-xs mt-1">{{ $message }}</div>
                @enderror
            </div>

            {{-- Reason Field --}}
            <div>
                <label for="reason" class="form-label font-medium text-neutral-700 dark:text-neutral-300">Alasan / Kendala Pengajuan Sesi Konseling</label>
                <textarea name="reason" id="reason" rows="6" placeholder="Tuliskan secara ringkas alasan atau kendala yang ingin dibicarakan dengan Guru BK..." class="form-control !rounded-lg @error('reason') is-invalid @enderror" required>{{ old('reason') }}</textarea>
                <span class="text-xs text-neutral-400 dark:text-neutral-500 mt-1 block">Minimal 10 karakter. Informasikan keluhan atau kebutuhan bimbingan dengan sopan.</span>
                @error('reason')
                    <div class="text-danger-600 text-xs mt-1">{{ $message }}</div>
                @enderror
            </div>

            {{-- Submit --}}
            <div class="flex justify-end gap-3 mt-2">
                <a href="{{ $backRoute }}" class="btn btn-outline-secondary !rounded-lg">Batal</a>
                <button type="submit" class="btn btn-primary-600 !rounded-lg flex items-center gap-2">
                    <iconify-icon icon="solar:disk-bold" class="text-lg"></iconify-icon>
                    Kirim Pengajuan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
