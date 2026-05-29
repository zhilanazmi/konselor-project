<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>KonselorKita - Ruang Aman Sekolah</title>
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com" rel="preconnect" />
    <link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect" />
    <link href="https://fonts.googleapis.com/css2?family=Lexend:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet" />
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <!-- Tailwind Config -->
    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "primary": "#137fec",
                        "primary-dark": "#0c5fb3",
                        "primary-light": "#e0f0ff",
                        "secondary": "#34d399", // Soft green for approachable feel
                        "background-light": "#f6f7f8",
                        "background-dark": "#101922",
                        "surface-light": "#ffffff",
                        "surface-dark": "#1a2632",
                    },
                    fontFamily: {
                        "display": ["Lexend", "sans-serif"],
                        "body": ["Lexend", "sans-serif"],
                    },
                    borderRadius: {
                        "DEFAULT": "0.25rem",
                        "lg": "0.5rem",
                        "xl": "0.75rem",
                        "2xl": "1rem",
                        "3xl": "1.5rem",
                        "full": "9999px"
                    },
                },
            },
        }
    </script>
</head>
<body class="bg-background-light dark:bg-background-dark text-slate-900 dark:text-slate-100 font-display antialiased overflow-x-hidden transition-colors duration-300">
    <div class="relative flex min-h-screen w-full flex-col group/design-root">
        <!-- Header -->
        <header class="sticky top-0 z-50 flex items-center justify-between whitespace-nowrap border-b border-solid border-slate-200 dark:border-slate-800 bg-surface-light/90 dark:bg-surface-dark/90 backdrop-blur-md px-6 lg:px-10 py-4">
            <div class="flex items-center gap-3">
                <div class="size-8 text-primary flex items-center justify-center">
                    <span class="material-symbols-outlined text-3xl">school</span>
                </div>
                <h2 class="text-slate-900 dark:text-white text-xl font-bold leading-tight tracking-[-0.015em]">KonselorKita</h2>
            </div>
            <div class="hidden md:flex flex-1 justify-end gap-8 items-center">
                <nav class="flex items-center gap-9">
                    <a class="text-slate-700 dark:text-slate-300 hover:text-primary dark:hover:text-primary transition-colors text-sm font-medium" href="#">Beranda</a>
                    <a class="text-slate-700 dark:text-slate-300 hover:text-primary dark:hover:text-primary transition-colors text-sm font-medium" href="#layanan">Layanan</a>
                    <a class="text-slate-700 dark:text-slate-300 hover:text-primary dark:hover:text-primary transition-colors text-sm font-medium" href="#tentang">Tentang Kami</a>
                </nav>
                <a href="{{ route('login') }}" class="flex min-w-[84px] cursor-pointer items-center justify-center overflow-hidden rounded-full h-10 px-6 bg-primary hover:bg-primary-dark transition-colors text-white text-sm font-bold shadow-lg shadow-primary/20">
                    <span class="truncate">Masuk Siswa</span>
                </a>
            </div>
            <!-- Mobile Menu Icon -->
            <button class="md:hidden p-2 text-slate-700 dark:text-slate-300">
                <span class="material-symbols-outlined">menu</span>
            </button>
        </header>
        <!-- Main Content -->
        <main class="flex-grow flex flex-col">
            <!-- Hero Section -->
            <section class="relative px-6 py-12 lg:px-20 lg:py-24 overflow-hidden bg-gradient-to-br from-primary-light/50 to-white dark:from-background-dark dark:to-surface-dark">
                <!-- Decorative Elements -->
                <div class="absolute top-20 right-[-5%] w-64 h-64 bg-secondary/10 rounded-full blur-3xl -z-10"></div>
                <div class="absolute bottom-10 left-[-5%] w-72 h-72 bg-primary/10 rounded-full blur-3xl -z-10"></div>
                <div class="max-w-7xl mx-auto grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                    <div class="flex flex-col gap-6 text-center lg:text-left z-10">
                        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-primary/10 text-primary w-fit mx-auto lg:mx-0">
                            <span class="material-symbols-outlined text-sm">verified_user</span>
                            <span class="text-xs font-bold uppercase tracking-wide">Rahasia Terjamin 100%</span>
                        </div>
                        <h1 class="text-slate-900 dark:text-white text-4xl lg:text-6xl font-black leading-[1.1] tracking-tight">
                            Ruang Aman untuk <span class="text-primary relative inline-block">
                                Bercerita
                                <svg class="absolute w-full h-3 bottom-1 left-0 text-secondary/30 -z-10" preserveAspectRatio="none" viewBox="0 0 100 10">
                                    <path d="M0 5 Q 50 10 100 5" fill="none" stroke="currentColor" stroke-width="8"></path>
                                </svg>
                            </span>
                        </h1>
                        <p class="text-slate-600 dark:text-slate-400 text-lg leading-relaxed max-w-lg mx-auto lg:mx-0">
                            Jangan pendam sendiri. Kami guru BK sekolahmu siap menjadi teman curhat dan mencari solusi bersama. Tanpa penghakiman.
                        </p>
                        <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start pt-4">
                            <a href="#" class="flex items-center justify-center gap-2 rounded-xl h-14 px-8 bg-primary hover:bg-primary-dark text-white text-base font-bold shadow-xl shadow-primary/25 transition-all hover:scale-105 active:scale-95">
                                <span class="material-symbols-outlined">chat_bubble</span>
                                <span>Curhat Yuk!</span>
                            </a>
                            <button class="flex items-center justify-center gap-2 rounded-xl h-14 px-8 bg-white dark:bg-surface-dark border border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-200 text-base font-bold hover:bg-slate-50 dark:hover:bg-slate-800 transition-all">
                                <span>Pelajari Dulu</span>
                            </button>
                        </div>
                        <p class="text-xs text-slate-500 dark:text-slate-500 mt-2 italic">*Kamu bisa melapor secara anonim jika belum siap membuka identitas.</p>
                    </div>
                    <div class="relative lg:h-auto min-h-[300px] flex justify-center items-center">
                        <div class="relative z-10 w-full aspect-square max-w-md rounded-3xl overflow-hidden shadow-2xl rotate-3 hover:rotate-0 transition-transform duration-500 border-8 border-white dark:border-surface-dark">
                            <img alt="Students studying together in a library setting, smiling and engaged" class="w-full h-full object-cover" src="https://lh3.googleusercontent.com/aida-public/AB6AXuBmg5a7j0S38pW5lNB3roDbKSL7a8X6-V83cm3c1kgdZt89wTK9G9N5ueDhwpff5adEerq8Q_RBnSbpjEJ8RjJd47D9VlqdhLhiyAjb8Q1bTxP9-cxt42ewrfExlKu_hwuiCDmaUHEunWVMFEmERLsbQyEILJLl1amhordmcNUrFXfaXAal2LSuv8MgmoZY9hkMtvcQ8tqAYkZoxe1hxaWVdlVVqWG7NcKCYwLYznDbekfMbaSzAyCo09-zzsBcfBzQcw_bFQ0mFCZB" />
                            <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent"></div>
                            <div class="absolute bottom-6 left-6 text-white">
                                <p class="font-bold text-lg">Konseling Bersama Ibu Ani</p>
                                <p class="text-sm opacity-90">Senin - Jumat, 08.00 - 15.00</p>
                            </div>
                        </div>
                        <!-- Floating Card Decoration -->
                        <div class="absolute -bottom-6 -left-6 bg-white dark:bg-surface-dark p-4 rounded-2xl shadow-xl z-20 flex items-center gap-3 animate-bounce" style="animation-duration: 3s;">
                            <div class="bg-green-100 dark:bg-green-900/30 p-2 rounded-full text-green-600 dark:text-green-400">
                                <span class="material-symbols-outlined">sentiment_satisfied</span>
                            </div>
                            <div>
                                <p class="text-sm font-bold text-slate-900 dark:text-white">Perasaan Lega</p>
                                <p class="text-xs text-slate-500">Setelah bercerita</p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <!-- Why Report Section -->
            <section class="py-16 px-6 lg:px-20 bg-surface-light dark:bg-surface-dark">
                <div class="max-w-7xl mx-auto">
                    <div class="text-center max-w-2xl mx-auto mb-12">
                        <h2 class="text-slate-900 dark:text-white text-3xl font-black mb-4">Kenapa Harus Cerita?</h2>
                        <p class="text-slate-600 dark:text-slate-400">Kami mengerti ketakutanmu. Di sini, keamanan dan kenyamananmu adalah prioritas nomor satu kami.</p>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                        <!-- Card 1 -->
                        <div class="group p-8 rounded-3xl bg-background-light dark:bg-background-dark border border-slate-100 dark:border-slate-800 hover:border-primary/30 transition-all hover:shadow-xl hover:-translate-y-1">
                            <div class="w-14 h-14 rounded-2xl bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                                <span class="material-symbols-outlined text-3xl">lock</span>
                            </div>
                            <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-3">Rahasia Terjamin</h3>
                            <p class="text-slate-600 dark:text-slate-400 leading-relaxed">Ceritamu hanya antara kamu dan konselor. Tidak akan bocor ke teman sekelas atau guru lain tanpa izinmu.</p>
                        </div>
                        <!-- Card 2 -->
                        <div class="group p-8 rounded-3xl bg-background-light dark:bg-background-dark border border-slate-100 dark:border-slate-800 hover:border-secondary/30 transition-all hover:shadow-xl hover:-translate-y-1">
                            <div class="w-14 h-14 rounded-2xl bg-green-100 dark:bg-green-900/30 text-green-600 dark:text-green-400 flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                                <span class="material-symbols-outlined text-3xl">favorite</span>
                            </div>
                            <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-3">Tanpa Penghakiman</h3>
                            <p class="text-slate-600 dark:text-slate-400 leading-relaxed">Kami mendengarkan dengan hati terbuka. Tidak ada yang salah atau benar, yang ada hanya perasaannmu.</p>
                        </div>
                        <!-- Card 3 -->
                        <div class="group p-8 rounded-3xl bg-background-light dark:bg-background-dark border border-slate-100 dark:border-slate-800 hover:border-purple-500/30 transition-all hover:shadow-xl hover:-translate-y-1">
                            <div class="w-14 h-14 rounded-2xl bg-purple-100 dark:bg-purple-900/30 text-purple-600 dark:text-purple-400 flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                                <span class="material-symbols-outlined text-3xl">lightbulb</span>
                            </div>
                            <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-3">Solusi Bersama</h3>
                            <p class="text-slate-600 dark:text-slate-400 leading-relaxed">Bukan cuma curhat, kita akan cari jalan keluar terbaik supaya kamu bisa kembali semangat sekolah.</p>
                        </div>
                    </div>
                </div>
            </section>
            <!-- Services Section -->
            <section class="py-16 px-6 lg:px-20 bg-primary-light/30 dark:bg-background-dark" id="layanan">
                <div class="max-w-7xl mx-auto">
                    <div class="flex flex-col md:flex-row justify-between items-end mb-10 gap-4">
                        <div>
                            <h2 class="text-slate-900 dark:text-white text-3xl font-black mb-2">Topik Populer</h2>
                            <p class="text-slate-600 dark:text-slate-400">Apa yang sedang mengganggu pikiranmu belakangan ini?</p>
                        </div>
                        <a class="text-primary font-bold hover:underline flex items-center gap-1" href="#">
                            Lihat semua topik <span class="material-symbols-outlined text-sm">arrow_forward</span>
                        </a>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                        <!-- Service Card 1 -->
                        <a class="group relative overflow-hidden rounded-2xl aspect-[4/5] bg-white dark:bg-surface-dark shadow-sm hover:shadow-xl transition-all" href="#">
                            <div class="absolute inset-0 bg-cover bg-center transition-transform duration-700 group-hover:scale-110" data-alt="Student looking stressed over textbooks" style="background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuDiK9807x5NkctZ1dcj-mRfVBdZcOun1fpR0tSr0ppAE5Ly85eGHRjqiDGfGgSKcdMJJuCQ6RjIiXWUeqERgwKhNcn_mY38uAHuTWfMZdmLOSPEn05gZSRqz2vPb_x0uzip66dqdCr2wiUD03SUdwnl5C2RiPwGCBQlSQyteAfdBH2rXLBklrA1JSGtQ73tAPgHfjPAd_k1Jd_sOJMoMjEJmSg6R0NDPlA2J6dvtNOfMH2Nvn4zr8n95JFIa9wDCsuouVo8ENyS1ntQ');"></div>
                            <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent"></div>
                            <div class="absolute bottom-0 left-0 p-6 w-full">
                                <div class="bg-primary w-10 h-10 rounded-lg flex items-center justify-center text-white mb-3">
                                    <span class="material-symbols-outlined">school</span>
                                </div>
                                <h3 class="text-white text-xl font-bold mb-1">Masalah Belajar</h3>
                                <p class="text-white/80 text-sm line-clamp-2">Susah fokus, nilai turun, atau bingung pilih jurusan?</p>
                            </div>
                        </a>
                        <!-- Service Card 2 -->
                        <a class="group relative overflow-hidden rounded-2xl aspect-[4/5] bg-white dark:bg-surface-dark shadow-sm hover:shadow-xl transition-all" href="#">
                            <div class="absolute inset-0 bg-cover bg-center transition-transform duration-700 group-hover:scale-110" data-alt="Group of teenagers talking, suggestive of social interaction" style="background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuBshc29BA6K3wcHq2SihvMg35lMsptLdcx8pjdQtT9iwJ78d2Fx_Q2cHpBAKPPTMA5OiH5_HMNaa-g69t3UzC-SmHN5CHV7M3k9PM_L7WK0JZD7RAVHdk2rSQkkgHY_02HCKk6NLxGMT-z5m9BsvDFhvXIagKv4R4NU1SwLU7eSJj7-xNl090-yedUPl34MGC4lxw3LXPmwQyo_Vco2p_cQiSRO3LJ4X77EkrCXSt4LXIW7dboCwDwmsF-ZjVzJo-5IrSnHbDxgWLpK');"></div>
                            <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent"></div>
                            <div class="absolute bottom-0 left-0 p-6 w-full">
                                <div class="bg-red-500 w-10 h-10 rounded-lg flex items-center justify-center text-white mb-3">
                                    <span class="material-symbols-outlined">groups</span>
                                </div>
                                <h3 class="text-white text-xl font-bold mb-1">Bullying & Teman</h3>
                                <p class="text-white/80 text-sm line-clamp-2">Dikucilkan, diejek, atau punya masalah dengan teman?</p>
                            </div>
                        </a>
                        <!-- Service Card 3 -->
                        <a class="group relative overflow-hidden rounded-2xl aspect-[4/5] bg-white dark:bg-surface-dark shadow-sm hover:shadow-xl transition-all" href="#">
                            <div class="absolute inset-0 bg-cover bg-center transition-transform duration-700 group-hover:scale-110" data-alt="Parent and child having a conversation" style="background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuCwTo_iSOPkmF5jjKYKk2Gd0kFJBvrmXPyNvX_si9_sFM9rA4amghKcwqU9nwJ-GZN_hvV6W43tchEKcv8gf0AqLO_rkVge2V5nWTDN6lmPPNlWnKoqk_8xZLE8Cz0yGw0tMBMURRnrwg9qss97v2OjHTDayao5DaMsn1JPpoAvGt6CdIsO7CH696XCEfIySFVIVb3mX86s_dgIangbpsZaKwxtFs57hxBsqBdC1mN72Onq1fsEDIKt5-z0NqdRTLCiJdGRae9e5hjz');"></div>
                            <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent"></div>
                            <div class="absolute bottom-0 left-0 p-6 w-full">
                                <div class="bg-orange-500 w-10 h-10 rounded-lg flex items-center justify-center text-white mb-3">
                                    <span class="material-symbols-outlined">home</span>
                                </div>
                                <h3 class="text-white text-xl font-bold mb-1">Keluarga</h3>
                                <p class="text-white/80 text-sm line-clamp-2">Masalah di rumah yang bikin kamu nggak nyaman.</p>
                            </div>
                        </a>
                        <!-- Service Card 4 -->
                        <a class="group relative overflow-hidden rounded-2xl aspect-[4/5] bg-white dark:bg-surface-dark shadow-sm hover:shadow-xl transition-all" href="#">
                            <div class="absolute inset-0 bg-cover bg-center transition-transform duration-700 group-hover:scale-110" data-alt="Person planning on a notebook, symbolizing career planning" style="background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuCz0lYR_7RlqFzL_zYv97W3PU8rvy441vA2ZGeVRFBTXMZQCuVKF07llROpYAlhQEAHFwBY1NPNSXjDJXmtpmcxcdL1rN_YRtnLz2_R8T4ZyFu-GdnNAeMP7JewFOcbzbd_JAr5ovQ4m4OsIo3RVtin4AD5H73yzAu6eQP99H7FLp9edpmqqHTjImDBa47VZLIl4Lj_5cafP81n2lcURNam_ZaXTw0fABvUPEi_q1-lgMGIPUwsK4r_Hu-NdrjMlVPMpNpkO_kAHLxy');"></div>
                            <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent"></div>
                            <div class="absolute bottom-0 left-0 p-6 w-full">
                                <div class="bg-secondary w-10 h-10 rounded-lg flex items-center justify-center text-white mb-3">
                                    <span class="material-symbols-outlined">rocket_launch</span>
                                </div>
                                <h3 class="text-white text-xl font-bold mb-1">Masa Depan</h3>
                                <p class="text-white/80 text-sm line-clamp-2">Bingung mau lanjut SMA atau SMK mana?</p>
                            </div>
                        </a>
                    </div>
                </div>
            </section>
            <!-- Testimonial Section -->
            <section class="py-16 px-6 lg:px-20 bg-surface-light dark:bg-surface-dark">
                <div class="max-w-5xl mx-auto">
                    <h2 class="text-center text-slate-900 dark:text-white text-3xl font-black mb-12">Kata Teman-Temanmu</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <!-- Testimonial 1 -->
                        <div class="bg-background-light dark:bg-background-dark p-8 rounded-3xl relative border border-slate-100 dark:border-slate-800">
                            <div class="absolute -top-4 left-8 text-primary/20">
                                <span class="material-symbols-outlined text-6xl">format_quote</span>
                            </div>
                            <p class="text-slate-700 dark:text-slate-300 text-lg italic mb-6 relative z-10">"Awalnya takut banget mau cerita soal bully di kelas. Tapi Ibu Guru BK beneran dengerin aku dan sekarang pelakunya udah ditegur. Makasih banyak!"</p>
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-full bg-blue-500 flex items-center justify-center text-white font-bold">R</div>
                                <div>
                                    <h4 class="font-bold text-slate-900 dark:text-white">Aldi </h4>
                                    <p class="text-xs text-slate-500">Kelas 8</p>
                                </div>
                            </div>
                        </div>
                        <!-- Testimonial 2 -->
                        <div class="bg-background-light dark:bg-background-dark p-8 rounded-3xl relative border border-slate-100 dark:border-slate-800">
                            <div class="absolute -top-4 left-8 text-secondary/20">
                                <span class="material-symbols-outlined text-6xl">format_quote</span>
                            </div>
                            <p class="text-slate-700 dark:text-slate-300 text-lg italic mb-6 relative z-10">"Aku sempet stress berat gara-gara nilai turun terus. Habis konseling, aku jadi tau cara belajar yang pas buat aku. Jadi lebih pede!"</p>
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-full bg-green-500 flex items-center justify-center text-white font-bold">D</div>
                                <div>
                                    <h4 class="font-bold text-slate-900 dark:text-white">Rama</h4>
                                    <p class="text-xs text-slate-500">Kelas 9</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <!-- CTA Footer Section -->
            <section class="py-20 px-6 lg:px-20 bg-slate-900 dark:bg-black text-white rounded-t-[40px] mt-auto">
                <div class="max-w-4xl mx-auto text-center">
                    <h2 class="text-3xl lg:text-5xl font-black mb-6">Siap untuk merasa lebih baik?</h2>
                    <p class="text-slate-300 text-lg mb-8 max-w-2xl mx-auto">Tidak ada masalah yang terlalu kecil atau terlalu besar. Kami ada di sini untuk mendengarkanmu.</p>
                    <div class="flex flex-col sm:flex-row gap-4 justify-center">
                        <a href="#" class="bg-primary hover:bg-primary-dark text-white px-8 py-4 rounded-xl font-bold text-lg transition-colors flex items-center justify-center gap-2">
                            <span class="material-symbols-outlined">edit_square</span>
                            Isi Formulir Curhat
                        </a>
                        <button class="bg-white/10 hover:bg-white/20 text-white border border-white/20 px-8 py-4 rounded-xl font-bold text-lg transition-colors flex items-center justify-center gap-2">
                            <span class="material-symbols-outlined">call</span>
                            Hotline Sekolah
                        </button>
                    </div>
                </div>
                <div class="mt-20 border-t border-white/10 pt-10 flex flex-col md:flex-row justify-between items-center gap-6 text-sm text-slate-400">
                    <div class="flex items-center gap-2">
                        <span class="material-symbols-outlined">school</span>
                        <span class="font-semibold text-white">KonselorKita</span> © 2026
                    </div>
                    <div class="flex gap-6">
                        <a class="hover:text-primary transition-colors" href="#">Tentang</a>
                        <a class="hover:text-primary transition-colors" href="#">Privasi</a>
                        <a class="hover:text-primary transition-colors" href="#">Panduan</a>
                    </div>
                    <p>Dibuat dengan ❤️ untuk Siswa Indonesia</p>
                </div>
            </section>
        </main>
        <!-- Floating Chat Widget -->
        <div class="fixed bottom-6 right-6 z-50">
            <button class="group flex items-center justify-center gap-2 bg-secondary hover:bg-green-500 text-white rounded-full p-4 pl-6 shadow-2xl transition-all hover:scale-105 animate-bounce-subtle">
                <span class="font-bold text-base">Butuh Bantuan Cepat?</span>
                <span class="bg-white text-secondary rounded-full p-1">
                    <span class="material-symbols-outlined text-xl">chat</span>
                </span>
            </button>
        </div>
    </div>
</body>
</html>
