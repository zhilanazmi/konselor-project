<aside class="sidebar">
  <button type="button" class="sidebar-close-btn !mt-4">
    <iconify-icon icon="radix-icons:cross-2"></iconify-icon>
  </button>
  <div>
    <a href="{{ url('/') }}" class="sidebar-logo">
      <img src="{{ asset('assets/images/logo.png') }}" alt="site logo" class="light-logo">
      <img src="{{ asset('assets/images/logo-light.png') }}" alt="site logo" class="dark-logo">
      <img src="{{ asset('assets/images/logo-icon.png') }}" alt="site logo" class="logo-icon">
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
      @endif

    </ul>
  </div>
</aside>

