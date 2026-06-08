@extends('layouts.app')

@section('title', 'Dashboard - KonselorKita')

@section('content')
<div class="grid grid-cols-1 gap-6">

    {{-- Welcome Card --}}
    <div class="card p-6">
        <div class="flex items-center gap-4">
            <div class="w-16 h-16 rounded-full bg-primary-600 flex items-center justify-center text-white text-2xl font-bold">
                {{ strtoupper(substr($user->name, 0, 1)) }}
            </div>
            <div>
                <h5 class="text-xl font-semibold mb-1">Selamat Datang, {{ $user->name }}!</h5>
                <span class="px-3 py-1 rounded-full text-xs font-medium
                    @switch($user->role->value)
                        @case('admin') bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400 @break
                        @case('guru_bk') bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400 @break
                        @case('guru') bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400 @break
                        @case('orang_tua') bg-orange-100 text-orange-700 dark:bg-orange-900/30 dark:text-orange-400 @break
                        @case('siswa') bg-purple-100 text-purple-700 dark:bg-purple-900/30 dark:text-purple-400 @break
                    @endswitch
                ">{{ $user->role->label() }}</span>
            </div>
        </div>
    </div>

    {{-- Role-specific dashboard content --}}
    @switch($user->role->value)

        @case('admin')
            <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-6">
                <div class="card p-5 flex items-center gap-4">
                    <div class="w-12 h-12 rounded-lg bg-primary-100 dark:bg-primary-900/30 flex items-center justify-center">
                        <iconify-icon icon="solar:users-group-rounded-bold" class="text-primary-600 text-2xl"></iconify-icon>
                    </div>
                    <div>
                        <p class="text-secondary-light text-sm mb-1">Total Siswa</p>
                        <h6 class="text-lg font-semibold">{{ \App\Models\Student::count() }}</h6>
                    </div>
                </div>
                <div class="card p-5 flex items-center gap-4">
                    <div class="w-12 h-12 rounded-lg bg-success-100 dark:bg-success-900/30 flex items-center justify-center">
                        <iconify-icon icon="solar:square-academic-cap-bold" class="text-success-600 text-2xl"></iconify-icon>
                    </div>
                    <div>
                        <p class="text-secondary-light text-sm mb-1">Total Guru</p>
                        <h6 class="text-lg font-semibold">{{ \App\Models\Teacher::count() }}</h6>
                    </div>
                </div>
                <div class="card p-5 flex items-center gap-4">
                    <div class="w-12 h-12 rounded-lg bg-warning-100 dark:bg-warning-900/30 flex items-center justify-center">
                        <iconify-icon icon="solar:buildings-bold" class="text-warning-600 text-2xl"></iconify-icon>
                    </div>
                    <div>
                        <p class="text-secondary-light text-sm mb-1">Total Kelas</p>
                        <h6 class="text-lg font-semibold">{{ \App\Models\Classroom::count() }}</h6>
                    </div>
                </div>
                <div class="card p-5 flex items-center gap-4">
                    <div class="w-12 h-12 rounded-lg bg-info-100 dark:bg-info-900/30 flex items-center justify-center">
                        <iconify-icon icon="solar:users-group-two-rounded-bold" class="text-info-600 text-2xl"></iconify-icon>
                    </div>
                    <div>
                        <p class="text-secondary-light text-sm mb-1">Total Orang Tua</p>
                        <h6 class="text-lg font-semibold">{{ \App\Models\Guardian::count() }}</h6>
                    </div>
                </div>
            </div>
        @break

        @case('guru_bk')
            <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-6">
                <div class="card p-5 flex items-center gap-4">
                    <div class="w-12 h-12 rounded-lg bg-primary-100 dark:bg-primary-900/30 flex items-center justify-center">
                        <iconify-icon icon="solar:chat-round-dots-bold" class="text-primary-600 text-2xl"></iconify-icon>
                    </div>
                    <div>
                        <p class="text-secondary-light text-sm mb-1">Konseling Individual</p>
                        <h6 class="text-lg font-semibold">{{ \App\Models\IndividualCounseling::where('counselor_id', $user->id)->count() }}</h6>
                    </div>
                </div>
                <div class="card p-5 flex items-center gap-4">
                    <div class="w-12 h-12 rounded-lg bg-success-100 dark:bg-success-900/30 flex items-center justify-center">
                        <iconify-icon icon="solar:users-group-rounded-bold" class="text-success-600 text-2xl"></iconify-icon>
                    </div>
                    <div>
                        <p class="text-secondary-light text-sm mb-1">Konseling Kelompok</p>
                        <h6 class="text-lg font-semibold">{{ \App\Models\GroupCounseling::where('counselor_id', $user->id)->count() }}</h6>
                    </div>
                </div>
                <div class="card p-5 flex items-center gap-4">
                    <div class="w-12 h-12 rounded-lg bg-warning-100 dark:bg-warning-900/30 flex items-center justify-center">
                        <iconify-icon icon="solar:clipboard-list-bold" class="text-warning-600 text-2xl"></iconify-icon>
                    </div>
                    <div>
                        <p class="text-secondary-light text-sm mb-1">Konsultasi Wali Kelas</p>
                        <h6 class="text-lg font-semibold">{{ \App\Models\HomeroomConsultation::where('counselor_id', $user->id)->count() }}</h6>
                    </div>
                </div>
                <div class="card p-5 flex items-center gap-4">
                    <div class="w-12 h-12 rounded-lg bg-info-100 dark:bg-info-900/30 flex items-center justify-center">
                        <iconify-icon icon="solar:inbox-line-bold" class="text-info-600 text-2xl"></iconify-icon>
                    </div>
                    <div>
                        <p class="text-secondary-light text-sm mb-1">Request Masuk</p>
                        <h6 class="text-lg font-semibold">{{ \App\Models\CounselingRequest::where('status', 'pending')->count() }}</h6>
                    </div>
                </div>
            </div>
        @break

        @case('guru')
            <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-6">
                <div class="card p-5 flex items-center gap-4">
                    <div class="w-12 h-12 rounded-lg bg-primary-100 dark:bg-primary-900/30 flex items-center justify-center">
                        <iconify-icon icon="solar:clipboard-list-bold" class="text-primary-600 text-2xl"></iconify-icon>
                    </div>
                    <div>
                        <p class="text-secondary-light text-sm mb-1">Konsultasi Wali Kelas</p>
                        <h6 class="text-lg font-semibold">
                            @if($user->teacher)
                                {{ \App\Models\HomeroomConsultation::where('teacher_id', $user->teacher->id)->count() }}
                            @else
                                0
                            @endif
                        </h6>
                    </div>
                </div>
                <div class="card p-5 flex items-center gap-4">
                    <div class="w-12 h-12 rounded-lg bg-success-100 dark:bg-success-900/30 flex items-center justify-center">
                        <iconify-icon icon="solar:book-bold" class="text-success-600 text-2xl"></iconify-icon>
                    </div>
                    <div>
                        <p class="text-secondary-light text-sm mb-1">Konsultasi Guru Mapel</p>
                        <h6 class="text-lg font-semibold">{{ $subjectConsultationCount ?? 0 }}</h6>
                    </div>
                </div>
                <div class="card p-5 flex items-center gap-4">
                    <div class="w-12 h-12 rounded-lg bg-warning-100 dark:bg-warning-900/30 flex items-center justify-center">
                        <iconify-icon icon="solar:buildings-bold" class="text-warning-600 text-2xl"></iconify-icon>
                    </div>
                    <div>
                        <p class="text-secondary-light text-sm mb-1">Kelas Perwalian</p>
                        <h6 class="text-lg font-semibold">{{ isset($classrooms) ? $classrooms->count() : 0 }}</h6>
                    </div>
                </div>
            </div>

            {{-- Kelas Perwalian Cards --}}
            @if(isset($classrooms) && $classrooms->isNotEmpty())
                <div class="card mt-6">
                    <div class="card-header flex items-center justify-between">
                        <h6 class="font-semibold mb-0 flex items-center gap-2">
                            <iconify-icon icon="solar:buildings-bold" class="text-primary-600"></iconify-icon>
                            Kelas Perwalian
                        </h6>
                        <a href="{{ route('guru.classrooms.index') }}" class="text-primary-600 text-sm hover:underline">Lihat Semua →</a>
                    </div>
                    <div class="card-body">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            @foreach($classrooms as $classroom)
                                <div class="flex items-center gap-3 p-3 rounded-lg border border-neutral-200 dark:border-neutral-700">
                                    <div class="w-10 h-10 rounded-lg bg-primary-100 dark:bg-primary-900/30 flex items-center justify-center text-primary-600">
                                        <iconify-icon icon="solar:buildings-bold" class="text-xl"></iconify-icon>
                                    </div>
                                    <div>
                                        <p class="font-medium mb-0">{{ $classroom->name }}</p>
                                        <p class="text-xs text-neutral-500 dark:text-neutral-400 mb-0">{{ $classroom->students_count }} siswa &bull; {{ $classroom->academicYear->name }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            {{-- Recent Consultations --}}
            @if(isset($recentConsultations) && $recentConsultations->isNotEmpty())
                <div class="card mt-4">
                    <div class="card-header flex items-center justify-between">
                        <h6 class="font-semibold mb-0 flex items-center gap-2">
                            <iconify-icon icon="solar:clipboard-list-bold" class="text-primary-600"></iconify-icon>
                            Konsultasi Terbaru
                        </h6>
                        <a href="{{ route('guru.homeroom-consultations.index') }}" class="text-primary-600 text-sm hover:underline">Lihat Semua →</a>
                    </div>
                    <div class="card-body">
                        <div class="flex flex-col gap-3">
                            @foreach($recentConsultations as $consultation)
                                <div class="flex items-center justify-between p-3 rounded-lg border border-neutral-200 dark:border-neutral-700">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full bg-info-100 dark:bg-info-900/30 flex items-center justify-center text-info-600 text-sm font-bold">
                                            {{ strtoupper(substr($consultation->student->full_name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <p class="font-medium mb-0 text-sm">{{ $consultation->student->full_name }}</p>
                                            <p class="text-xs text-neutral-500 dark:text-neutral-400 mb-0">{{ Str::limit($consultation->topic, 40) }}</p>
                                        </div>
                                    </div>
                                    <span class="text-xs text-neutral-400">{{ $consultation->consultation_date->format('d M Y') }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
        @break

        @case('orang_tua')
            {{-- Children Cards --}}
            @if(isset($children) && $children->isNotEmpty())
                <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-6">
                    @foreach($children as $child)
                        @php
                            $activeClassroom = $child->classrooms
                                ->sortByDesc(fn ($c) => $c->academicYear->is_active ?? false)
                                ->first();
                        @endphp
                        <div class="card p-5">
                            <div class="flex items-start gap-3 mb-3">
                                <div class="w-12 h-12 rounded-full bg-primary-100 dark:bg-primary-900/30 flex items-center justify-center text-primary-600 text-lg font-bold flex-shrink-0">
                                    {{ strtoupper(substr($child->full_name, 0, 1)) }}
                                </div>
                                <div>
                                    <h6 class="font-semibold mb-0.5">{{ $child->full_name }}</h6>
                                    <p class="text-xs text-neutral-500 dark:text-neutral-400 mb-0">NIS: {{ $child->nis }}</p>
                                </div>
                            </div>
                            <div class="flex flex-col gap-1.5 text-sm mb-3">
                                <div class="flex items-center gap-2 text-neutral-600 dark:text-neutral-400">
                                    <iconify-icon icon="solar:buildings-bold" class="text-base text-primary-500"></iconify-icon>
                                    <span>Kelas: <strong>{{ $activeClassroom?->name ?? '-' }}</strong></span>
                                </div>
                                <div class="flex items-center gap-2 text-neutral-600 dark:text-neutral-400">
                                    <iconify-icon icon="solar:chat-round-dots-bold" class="text-base text-primary-500"></iconify-icon>
                                    <span>Sesi Konseling: <strong>{{ $child->individual_counselings_count }}</strong></span>
                                </div>
                            </div>
                            <a href="{{ route('orang-tua.children.counselings', $child) }}" class="btn btn-primary-600 w-full !rounded-lg flex items-center justify-center gap-2 !text-sm !py-1.5">
                                <iconify-icon icon="solar:eye-bold"></iconify-icon>
                                Lihat Riwayat
                            </a>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="card p-6">
                    <h6 class="text-lg font-semibold mb-4">Informasi Anak</h6>
                    <p class="text-secondary-light">Belum ada data anak yang terhubung dengan akun Anda.</p>
                </div>
            @endif

            {{-- Recent Parent Consultations --}}
            @if(isset($recentConsultations) && $recentConsultations->isNotEmpty())
                <div class="card mt-4">
                    <div class="card-header flex items-center justify-between">
                        <h6 class="font-semibold mb-0 flex items-center gap-2">
                            <iconify-icon icon="solar:clipboard-list-bold" class="text-primary-600"></iconify-icon>
                            Konsultasi Terbaru
                        </h6>
                        <a href="{{ route('orang-tua.consultations.index') }}" class="text-primary-600 text-sm hover:underline">Lihat Semua →</a>
                    </div>
                    <div class="card-body">
                        <div class="flex flex-col gap-3">
                            @foreach($recentConsultations as $consultation)
                                <div class="flex items-center justify-between p-3 rounded-lg border border-neutral-200 dark:border-neutral-700">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full bg-orange-100 dark:bg-orange-900/30 flex items-center justify-center text-orange-600 text-sm font-bold">
                                            {{ strtoupper(substr($consultation->student->full_name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <p class="font-medium mb-0 text-sm">{{ $consultation->student->full_name }}</p>
                                            <p class="text-xs text-neutral-500 dark:text-neutral-400 mb-0">Konselor: {{ $consultation->counselor->name }}</p>
                                        </div>
                                    </div>
                                    <span class="text-xs text-neutral-400">{{ $consultation->scheduled_at->format('d M Y') }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
        @break

        @case('siswa')
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                <div class="card p-5 flex items-center gap-4">
                    <div class="w-12 h-12 rounded-lg bg-primary-100 dark:bg-primary-900/30 flex items-center justify-center">
                        <iconify-icon icon="solar:chat-round-dots-bold" class="text-primary-600 text-2xl"></iconify-icon>
                    </div>
                    <div>
                        <p class="text-secondary-light text-sm mb-1">Konseling Individual</p>
                        <h6 class="text-lg font-semibold">
                            @if($user->student)
                                {{ \App\Models\IndividualCounseling::where('student_id', $user->student->id)->count() }}
                            @else
                                0
                            @endif
                        </h6>
                    </div>
                </div>
                <div class="card p-5 flex items-center gap-4">
                    <div class="w-12 h-12 rounded-lg bg-success-100 dark:bg-success-900/30 flex items-center justify-center">
                        <iconify-icon icon="solar:users-group-rounded-bold" class="text-success-600 text-2xl"></iconify-icon>
                    </div>
                    <div>
                        <p class="text-secondary-light text-sm mb-1">Konseling Kelompok</p>
                        <h6 class="text-lg font-semibold">{{ $groupCounselingCount ?? 0 }}</h6>
                    </div>
                </div>
                <div class="card p-5 flex items-center gap-4">
                    <div class="w-12 h-12 rounded-lg bg-warning-100 dark:bg-warning-900/30 flex items-center justify-center">
                        <iconify-icon icon="solar:inbox-line-bold" class="text-warning-600 text-2xl"></iconify-icon>
                    </div>
                    <div>
                        <p class="text-secondary-light text-sm mb-1">Request Konseling</p>
                        <h6 class="text-lg font-semibold">
                            @if($user->student)
                                {{ \App\Models\CounselingRequest::where('student_id', $user->student->id)->count() }}
                            @else
                                0
                            @endif
                        </h6>
                    </div>
                </div>
            </div>

            {{-- Recent Counseling Sessions --}}
            @if(isset($recentCounselings) && $recentCounselings->isNotEmpty())
                <div class="card mt-4">
                    <div class="card-header flex items-center justify-between">
                        <h6 class="font-semibold mb-0 flex items-center gap-2">
                            <iconify-icon icon="solar:chat-round-dots-bold" class="text-primary-600"></iconify-icon>
                            Konseling Terbaru
                        </h6>
                        <a href="{{ route('siswa.counselings.index') }}" class="text-primary-600 text-sm hover:underline">Lihat Semua →</a>
                    </div>
                    <div class="card-body">
                        <div class="flex flex-col gap-3">
                            @foreach($recentCounselings as $counseling)
                                @php
                                    $categoryColors = [
                                        'pribadi' => 'bg-purple-100 text-purple-600 dark:bg-purple-900/30 dark:text-purple-400',
                                        'sosial' => 'bg-blue-100 text-blue-600 dark:bg-blue-900/30 dark:text-blue-400',
                                        'belajar' => 'bg-amber-100 text-amber-600 dark:bg-amber-900/30 dark:text-amber-400',
                                        'karir' => 'bg-green-100 text-green-600 dark:bg-green-900/30 dark:text-green-400',
                                    ];
                                    $statusLabels = [
                                        'scheduled' => 'Dijadwalkan',
                                        'ongoing' => 'Berlangsung',
                                        'completed' => 'Selesai',
                                        'followed_up' => 'Tindak Lanjut',
                                    ];
                                @endphp
                                <a href="{{ route('siswa.counselings.show', $counseling) }}" class="flex items-center justify-between p-3 rounded-lg border border-neutral-200 dark:border-neutral-700 hover:bg-neutral-50 dark:hover:bg-neutral-800 transition-colors">
                                    <div class="flex items-center gap-3">
                                        <span class="px-2 py-0.5 rounded text-xs font-medium {{ $categoryColors[$counseling->category] ?? '' }}">
                                            {{ ucfirst($counseling->category) }}
                                        </span>
                                        <span class="text-sm font-medium">{{ $counseling->counselor->name }}</span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <span class="text-xs text-neutral-400">{{ $counseling->scheduled_at->format('d M Y') }}</span>
                                        <span class="text-xs text-neutral-400">{{ $statusLabels[$counseling->status] ?? $counseling->status }}</span>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
        @break

    @endswitch

</div>
@endsection

