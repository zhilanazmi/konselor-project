@extends('layouts.app')

@section('title', 'Kelas Perwalian - KonselorKita')

@section('content')

<div class="card mb-4">
    <div class="card-header">
        <h6 class="text-lg font-semibold mb-0">Kelas Perwalian Saya</h6>
    </div>
    <div class="card-body">
        <p class="text-sm text-neutral-500 dark:text-neutral-400 mb-4">Daftar kelas yang Anda ampu sebagai wali kelas beserta daftar siswa di dalamnya.</p>

        @if($classrooms->isEmpty())
            <div class="flex flex-col items-center gap-2 text-neutral-400 py-8">
                <iconify-icon icon="solar:buildings-bold" class="text-4xl"></iconify-icon>
                <p class="mb-0">Anda belum ditunjuk sebagai wali kelas untuk kelas manapun.</p>
            </div>
        @else
            @foreach($classrooms as $classroom)
                <div class="card border border-neutral-200 dark:border-neutral-700 mb-4 last:mb-0">
                    <div class="card-header flex flex-wrap items-center justify-between gap-2">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-lg bg-primary-100 dark:bg-primary-900/30 flex items-center justify-center text-primary-600">
                                <iconify-icon icon="solar:buildings-bold" class="text-xl"></iconify-icon>
                            </div>
                            <div>
                                <h6 class="font-semibold mb-0">{{ $classroom->name }}</h6>
                                <p class="text-xs text-neutral-500 dark:text-neutral-400 mb-0">
                                    Tingkat {{ $classroom->grade }} &bull; {{ $classroom->academicYear->name }}
                                </p>
                            </div>
                        </div>
                        <span class="px-3 py-1 rounded-full text-xs font-semibold bg-primary-100 text-primary-600 dark:bg-primary-900/30 dark:text-primary-400">
                            {{ $classroom->students->count() }} Siswa
                        </span>
                    </div>
                    <div class="card-body">
                        @if($classroom->students->isEmpty())
                            <p class="text-neutral-400 italic mb-0">Belum ada siswa di kelas ini.</p>
                        @else
                            <div class="overflow-x-auto">
                                <table class="table bordered-table mb-0">
                                    <thead>
                                        <tr>
                                            <th scope="col" class="!text-center w-12">No</th>
                                            <th scope="col">NIS</th>
                                            <th scope="col">Nama Lengkap</th>
                                            <th scope="col" class="!text-center">Jenis Kelamin</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($classroom->students->sortBy('full_name') as $index => $student)
                                            <tr>
                                                <td class="!text-center">{{ $index + 1 }}</td>
                                                <td>{{ $student->nis }}</td>
                                                <td><span class="font-medium">{{ $student->full_name }}</span></td>
                                                <td class="!text-center">
                                                    @if($student->gender === 'L')
                                                        <span class="px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-600 dark:bg-blue-900/30 dark:text-blue-400">Laki-laki</span>
                                                    @else
                                                        <span class="px-2 py-0.5 rounded text-xs font-medium bg-pink-100 text-pink-600 dark:bg-pink-900/30 dark:text-pink-400">Perempuan</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        @endif
    </div>
</div>
@endsection
