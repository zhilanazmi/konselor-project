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
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
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
                        <h6 class="text-lg font-semibold">
                            @if($user->teacher)
                                {{ \App\Models\SubjectTeacherConsultation::where('teacher_id', $user->teacher->id)->count() }}
                            @else
                                0
                            @endif
                        </h6>
                    </div>
                </div>
            </div>
        @break

        @case('orang_tua')
            <div class="card p-6">
                <h6 class="text-lg font-semibold mb-4">Informasi Anak</h6>
                <p class="text-secondary-light">Anda dapat melihat perkembangan konseling anak Anda melalui menu di sidebar.</p>
            </div>
        @break

        @case('siswa')
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div class="card p-5 flex items-center gap-4">
                    <div class="w-12 h-12 rounded-lg bg-primary-100 dark:bg-primary-900/30 flex items-center justify-center">
                        <iconify-icon icon="solar:chat-round-dots-bold" class="text-primary-600 text-2xl"></iconify-icon>
                    </div>
                    <div>
                        <p class="text-secondary-light text-sm mb-1">Sesi Konseling Saya</p>
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
        @break

    @endswitch

</div>
@endsection
