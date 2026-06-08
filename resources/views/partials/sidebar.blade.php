<aside class="sidebar">
  <button type="button" class="sidebar-close-btn !mt-4">
    <iconify-icon icon="radix-icons:cross-2"></iconify-icon>
  </button>
  <div>
    <a href="{{ url('/') }}" class="sidebar-logo">
      <img src="{{ asset('assets/images/dasbor.png') }}" alt="site logo" class="light-logo">
      <img src="{{ asset('assets/images/dasbor.png') }}" alt="site logo" class="dark-logo">
      <img src="{{ asset('assets/images/dasbor.png') }}" alt="site logo" class="logo-icon">
    </a>
  </div>
  <div class="sidebar-menu-area">
    <ul class="sidebar-menu" id="sidebar-menu">

      {{-- Dashboard --}}
      <li>
        <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active-page' : '' }}">
          <iconify-icon icon="solar:home-smile-angle-outline" class="menu-icon"></iconify-icon>
          <span>Dashboard</span>
        </a>
      </li>

      {{-- Admin: Data Master --}}
      @if(auth()->user()->role === \App\Enums\UserRole::Admin)
        <li class="sidebar-menu-group-title">Data Master</li>
        <li>
          <a href="{{ route('admin.academic-years.index') }}" class="{{ request()->routeIs('admin.academic-years.*') ? 'active-page' : '' }}">
            <iconify-icon icon="solar:calendar-bold" class="menu-icon"></iconify-icon>
            <span>Tahun Ajaran</span>
          </a>
        </li>
        <li>
          <a href="{{ route('admin.classrooms.index') }}" class="{{ request()->routeIs('admin.classrooms.*') ? 'active-page' : '' }}">
            <iconify-icon icon="solar:buildings-bold" class="menu-icon"></iconify-icon>
            <span>Kelas</span>
          </a>
        </li>
        <li>
          <a href="{{ route('admin.students.index') }}" class="{{ request()->routeIs('admin.students.*') ? 'active-page' : '' }}">
            <iconify-icon icon="solar:users-group-rounded-bold" class="menu-icon"></iconify-icon>
            <span>Siswa</span>
          </a>
        </li>
        <li>
          <a href="{{ route('admin.teachers.index') }}" class="{{ request()->routeIs('admin.teachers.*') ? 'active-page' : '' }}">
            <iconify-icon icon="solar:user-id-bold" class="menu-icon"></iconify-icon>
            <span>Guru / Wali Kelas</span>
          </a>
        </li>
        <li>
          <a href="{{ route('admin.guardians.index') }}" class="{{ request()->routeIs('admin.guardians.*') ? 'active-page' : '' }}">
            <iconify-icon icon="solar:users-group-two-rounded-bold" class="menu-icon"></iconify-icon>
            <span>Orang Tua</span>
          </a>
        </li>

        <li class="sidebar-menu-group-title">Pengaturan</li>
        <li>
          <a href="{{ route('admin.users.index') }}" class="{{ request()->routeIs('admin.users.*') ? 'active-page' : '' }}">
            <iconify-icon icon="solar:shield-user-bold" class="menu-icon"></iconify-icon>
            <span>Akun User</span>
          </a>
        </li>
      @endif

      {{-- Guru BK: Layanan Konseling --}}
      @if(auth()->user()->role === \App\Enums\UserRole::GuruBk)
        <li class="sidebar-menu-group-title">Layanan Konseling</li>
        <li>
          <a href="{{ route('guru-bk.individual-counselings.index') }}" class="{{ request()->routeIs('guru-bk.individual-counselings.*') ? 'active-page' : '' }}">
            <iconify-icon icon="solar:user-speak-bold" class="menu-icon"></iconify-icon>
            <span>Konseling Individual</span>
          </a>
        </li>
        <li>
          <a href="{{ route('guru-bk.group-counselings.index') }}" class="{{ request()->routeIs('guru-bk.group-counselings.*') ? 'active-page' : '' }}">
            <iconify-icon icon="solar:users-group-rounded-bold" class="menu-icon"></iconify-icon>
            <span>Konseling Kelompok</span>
          </a>
        </li>
        <li>
          <a href="{{ route('guru-bk.homeroom-consultations.index') }}" class="{{ request()->routeIs('guru-bk.homeroom-consultations.*') ? 'active-page' : '' }}">
            <iconify-icon icon="solar:user-speak-bold" class="menu-icon"></iconify-icon>
            <span>Konsultasi Wali Kelas</span>
          </a>
        </li>
        <li>
          <a href="{{ route('guru-bk.subject-teacher-consultations.index') }}" class="{{ request()->routeIs('guru-bk.subject-teacher-consultations.*') ? 'active-page' : '' }}">
            <iconify-icon icon="solar:book-bold" class="menu-icon"></iconify-icon>
            <span>Konsultasi Guru Mapel</span>
          </a>
        </li>
        <li>
          <a href="{{ route('guru-bk.parent-consultations.index') }}" class="{{ request()->routeIs('guru-bk.parent-consultations.*') ? 'active-page' : '' }}">
            <iconify-icon icon="solar:users-group-rounded-bold" class="menu-icon"></iconify-icon>
            <span>Konsultasi Orang Tua</span>
          </a>
        </li>
        @php
          $pendingRequestsCount = \App\Models\CounselingRequest::query()->where('status', 'pending')->count();
        @endphp
        <li>
          <a href="{{ route('guru-bk.counseling-requests.index') }}" class="{{ request()->routeIs('guru-bk.counseling-requests.*') ? 'active-page' : '' }} flex items-center justify-between">
            <div class="flex items-center gap-2">
              <iconify-icon icon="solar:inbox-line-bold" class="menu-icon"></iconify-icon>
              <span>Permohonan Konseling</span>
            </div>
            @if($pendingRequestsCount > 0)
              <span class="px-2 py-0.5 text-xs font-semibold bg-warning-100 text-warning-700 dark:bg-warning-900/30 dark:text-warning-400 rounded-full">{{ $pendingRequestsCount }}</span>
            @endif
          </a>
        </li>
      @endif

      {{-- Siswa: Layanan Mandiri --}}
      @if(auth()->user()->role === \App\Enums\UserRole::Siswa)
        <li class="sidebar-menu-group-title">Layanan Mandiri</li>
        <li>
          <a href="{{ route('siswa.counseling-requests.index') }}" class="{{ request()->routeIs('siswa.counseling-requests.*') ? 'active-page' : '' }}">
            <iconify-icon icon="solar:chat-round-dots-bold" class="menu-icon"></iconify-icon>
            <span>Pengajuan Konseling</span>
          </a>
        </li>
        <li class="sidebar-menu-group-title">Riwayat Saya</li>
        <li>
          <a href="{{ route('siswa.counselings.index') }}" class="{{ request()->routeIs('siswa.counselings.*') ? 'active-page' : '' }}">
            <iconify-icon icon="solar:user-speak-bold" class="menu-icon"></iconify-icon>
            <span>Konseling Individual</span>
          </a>
        </li>
        <li>
          <a href="{{ route('siswa.group-counselings.index') }}" class="{{ request()->routeIs('siswa.group-counselings.*') ? 'active-page' : '' }}">
            <iconify-icon icon="solar:users-group-rounded-bold" class="menu-icon"></iconify-icon>
            <span>Konseling Kelompok</span>
          </a>
        </li>
      @endif

      {{-- Orang Tua: Layanan Wali --}}
      @if(auth()->user()->role === \App\Enums\UserRole::OrangTua)
        <li class="sidebar-menu-group-title">Layanan Wali</li>
        <li>
          <a href="{{ route('orang-tua.counseling-requests.index') }}" class="{{ request()->routeIs('orang-tua.counseling-requests.*') ? 'active-page' : '' }}">
            <iconify-icon icon="solar:chat-round-dots-bold" class="menu-icon"></iconify-icon>
            <span>Pengajuan Konseling Anak</span>
          </a>
        </li>
        <li class="sidebar-menu-group-title">Monitoring Anak</li>
        <li>
          <a href="{{ route('orang-tua.children.index') }}" class="{{ request()->routeIs('orang-tua.children.*') ? 'active-page' : '' }}">
            <iconify-icon icon="solar:users-group-rounded-bold" class="menu-icon"></iconify-icon>
            <span>Informasi Anak</span>
          </a>
        </li>
        <li>
          <a href="{{ route('orang-tua.consultations.index') }}" class="{{ request()->routeIs('orang-tua.consultations.*') ? 'active-page' : '' }}">
            <iconify-icon icon="solar:clipboard-list-bold" class="menu-icon"></iconify-icon>
            <span>Riwayat Konsultasi</span>
          </a>
        </li>
      @endif

      {{-- Guru (Non-BK): Portal Guru --}}
      @if(auth()->user()->role === \App\Enums\UserRole::Guru)
        <li class="sidebar-menu-group-title">Konsultasi Saya</li>
        <li>
          <a href="{{ route('guru.homeroom-consultations.index') }}" class="{{ request()->routeIs('guru.homeroom-consultations.*') ? 'active-page' : '' }}">
            <iconify-icon icon="solar:user-speak-bold" class="menu-icon"></iconify-icon>
            <span>Konsultasi Wali Kelas</span>
          </a>
        </li>
        <li>
          <a href="{{ route('guru.subject-consultations.index') }}" class="{{ request()->routeIs('guru.subject-consultations.*') ? 'active-page' : '' }}">
            <iconify-icon icon="solar:book-bold" class="menu-icon"></iconify-icon>
            <span>Konsultasi Guru Mapel</span>
          </a>
        </li>
        <li class="sidebar-menu-group-title">Kelas Perwalian</li>
        <li>
          <a href="{{ route('guru.classrooms.index') }}" class="{{ request()->routeIs('guru.classrooms.*') ? 'active-page' : '' }}">
            <iconify-icon icon="solar:buildings-bold" class="menu-icon"></iconify-icon>
            <span>Kelas Saya</span>
          </a>
        </li>
      @endif

    </ul>
  </div>
</aside>

