@extends('layouts.auth')

@section('title', 'Login - KonselorKita')

@section('content')
<section class="bg-white dark:bg-dark-2 flex flex-wrap min-h-[100vh]">
    <div class="lg:w-1/2 lg:block hidden">
        <div class="flex items-center flex-col h-full justify-center">
            <img src="{{ asset('assets/images/auth/auth-img.png') }}" alt="">
        </div>
    </div>
    <div class="lg:w-1/2 py-8 px-6 flex flex-col justify-center">
        <div class="lg:max-w-[464px] mx-auto w-full">
            <div>
                <center><a href="{{ url('/') }}" class="mb-2.5 max-w-[290px]">
                    <img src="{{ asset('assets/images/ok - Copy (4).png') }}" alt="">
                </a></center>
                <center><h4 class="mb-3">Masuk ke Akun Anda</h4></center>
               <center> <p class="mb-8 text-secondary-light text-lg">Selamat datang kembali! Silakan masukkan detail login Anda.</p></center>
            </div>

            @if ($errors->any())
            <div class="mb-4 p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-xl">
                @foreach ($errors->all() as $error)
                    <p class="text-red-600 dark:text-red-400 text-sm">{{ $error }}</p>
                @endforeach
            </div>
            @endif

            <form action="{{ route('login') }}" method="POST">
                @csrf
                <div class="icon-field mb-4 relative">
                    <span class="absolute start-4 top-1/2 -translate-y-1/2 pointer-events-none flex text-xl">
                        <iconify-icon icon="mage:email"></iconify-icon>
                    </span>
                    <input type="email" name="email" value="{{ old('email') }}" class="form-control h-[56px] ps-11 border-neutral-300 bg-neutral-50 dark:bg-dark-2 rounded-xl" placeholder="Email" required>
                </div>
                <div class="relative mb-5">
                    <div class="icon-field">
                        <span class="absolute start-4 top-1/2 -translate-y-1/2 pointer-events-none flex text-xl">
                            <iconify-icon icon="solar:lock-password-outline"></iconify-icon>
                        </span>
                        <input type="password" name="password" class="form-control h-[56px] ps-11 border-neutral-300 bg-neutral-50 dark:bg-dark-2 rounded-xl" id="your-password" placeholder="Password" required>
                    </div>
                    <span class="toggle-password ri-eye-line cursor-pointer absolute end-0 top-1/2 -translate-y-1/2 me-4 text-secondary-light" data-toggle="#your-password"></span>
                </div>
                <div class="mt-7">
                    <div class="flex items-center">
                        <input class="form-check-input border border-neutral-300" type="checkbox" name="remember" value="1" id="remember">
                        <label class="ps-2" for="remember">Ingat saya</label>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary justify-center text-sm btn-sm px-3 py-4 w-full rounded-xl mt-8">Masuk</button>
            </form>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
    function initializePasswordToggle(toggleSelector) {
        $(toggleSelector).on('click', function() {
            $(this).toggleClass("ri-eye-off-line");
            var input = $($(this).attr("data-toggle"));
            if (input.attr("type") === "password") {
                input.attr("type", "text");
            } else {
                input.attr("type", "password");
            }
        });
    }
    initializePasswordToggle('.toggle-password');
</script>
@endpush
