# Product Requirement Document (PRD) - KonselorKita
**Sistem Informasi Bimbingan Konseling (BK) Sekolah**

## 1. Deskripsi Produk
**KonselorKita** adalah platform web manajemen Bimbingan Konseling (BK) sekolah berbasis Laravel 11. Aplikasi ini didesain untuk mendigitalisasi, menyederhanakan, dan menstrukturkan pencatatan administrasi data master siswa, kelas, guru, orang tua, serta seluruh alur layanan bimbingan konseling dan konsultasi sekolah secara terintegrasi, transparan, dan aman.

---

## 2. Peran Pengguna (User Roles)
Aplikasi ini memiliki 5 peran pengguna dengan tingkat akses berbeda:
1. **Administrator (Admin)**:
   - Mengelola master data: Tahun Ajaran, Kelas, Siswa, Guru, Orang Tua, Akun Pengguna.
   - Mengalokasikan siswa ke dalam kelas (tambah/hapus siswa kelas).
2. **Guru Bimbingan Konseling (Guru BK / Konselor)**:
   - Mengelola layanan bimbingan konseling individual dan kelompok.
   - Mengelola sesi konsultasi dengan Wali Kelas, Guru Mata Pelajaran, dan Orang Tua.
   - Melihat permohonan konseling masuk dari siswa/orang tua (fitur backend siap, frontend & controller belum selesai).
3. **Guru (Wali Kelas / Guru Mata Pelajaran)**:
   - Berkolaborasi dengan Guru BK dalam sesi konsultasi untuk membahas perkembangan/permasalahan siswa.
4. **Orang Tua / Wali**:
   - Memantau perkembangan hasil konseling anak (bersifat terbatas/non-rahasia).
   - Mengajukan permohonan konseling untuk anaknya ke Guru BK.
5. **Siswa**:
   - Melihat riwayat konseling pribadi.
   - Mengajukan permohonan konseling mandiri ke Guru BK.

---

## 3. Status Implementasi Berdasarkan Fase (Mei 2026)

Proyek ini dikembangkan secara bertahap melalui sistem **Fase Kerja**. Berikut adalah status implementasi terkini dari masing-masing fase:

### 🟢 Phase 1: Setup Foundation & Role-Based Dashboard (100% Selesai & Teruji)
*Fondasi dasar aplikasi, database, otentikasi, dan pembagian hak akses:*
- **Skema Database & Migrasi**: 14 migrasi tabel inti (`users`, `academic_years`, `students`, `teachers`, `classrooms`, dll.).
- **Eloquent Models & Relationships**: 12 model Eloquent yang saling terhubung lengkap dengan model factory dan seeder data demo.
- **Otentikasi & Otorisasi**: Sistem Login/Logout dengan perlindungan route menggunakan `RoleMiddleware` kustom (`admin`, `guru_bk`, `guru`, `orang_tua`, `siswa`).
- **Dashboard Multi-Peran**: Tampilan dashboard dinamis dengan widget statistik dan aktivitas terbaru yang menyesuaikan peran pengguna yang sedang aktif.

### 🟢 Phase 2: Master Data Management - Admin Portal (100% Selesai & Teruji)
*Portal administrasi lengkap untuk mengelola data master sekolah:*
- **Tahun Ajaran**: CRUD tahun ajaran dengan validasi eksklusivitas tahun ajaran aktif (mengaktifkan satu akan otomatis menonaktifkan tahun ajaran lainnya).
- **Kelas**: CRUD data kelas (tingkat 7, 8, 9), penunjukan wali kelas, serta pengalokasian siswa ke dalam kelas (menambah/menghapus siswa secara dinamis).
- **Siswa**: CRUD data siswa beserta sinkronisasi otomatis pembuatan/penghapusan akun pengguna (`User`).
- **Guru / Wali Kelas**: CRUD data guru beserta mata pelajaran yang diampu.
- **Orang Tua / Wali**: CRUD data orang tua beserta relasi hubungan dengan siswa (ayah, ibu, wali).
- **Manajemen Akun**: CRUD akun pengguna untuk sinkronisasi otentikasi login.

### 🟢 Phase 3: Core Counseling Services (100% Selesai & Teruji)
*Fitur utama bimbingan konseling untuk Guru BK:*
- **Konseling Individual**: Pencatatan sesi konseling 1-on-1 dengan siswa. Meliputi kategori masalah (Pribadi, Sosial, Belajar, Karir), deskripsi masalah, rencana tindak lanjut, status (open/progress/resolved), dan tanggal pelaksanaan.
- **Konseling Kelompok**: Pencatatan sesi konseling berkelompok. Mendukung penambahan beberapa partisipan siswa secara dinamis (many-to-many), serta pengisian catatan khusus/individual untuk masing-masing siswa yang terlibat.

### 🟢 Phase 4: Collaborative Consultations (100% Selesai & Teruji)
*Fitur kolaborasi penanganan siswa antara Guru BK dengan pihak internal dan eksternal:*
- **Konsultasi Wali Kelas**: Sesi kolaborasi antara Guru BK dengan Wali Kelas untuk membahas kasus siswa tertentu di kelasnya.
- **Konsultasi Guru Mapel**: Sesi kolaborasi antara Guru BK dengan Guru Mata Pelajaran terkait kendala belajar siswa di kelas.
- **Konsultasi Orang Tua**: Sesi pertemuan dan konsultasi antara Guru BK dengan Orang Tua siswa terkait penanganan masalah anak di rumah.

---

### 🟡 Tampilan Antarmuka (UI/UX) (90% Selesai)
- **Tema & Gaya**: Menggunakan template modern premium **Wowdash Tailwind CSS Admin Dashboard**.
- **Aset & Ikon**: Menggunakan pustaka Iconify dan Remix Icons untuk visual yang modern dan premium.
- **Mode Gelap**: Didukung penuh oleh gaya dark mode Tailwind.
- **Menu Navigasi**: Sidebar dan header dinamis sudah diatur berdasarkan peran pengguna (saat ini menu berfokus pada peran **Admin** dan **Guru BK**).
- **Dashboard Widget**: Menampilkan statistik real-time yang akurat sesuai dengan peran masing-masing pengguna (misal: jumlah siswa/kelas untuk Admin, jumlah sesi konseling/request masuk untuk Guru BK).

---

## 4. Struktur Database & Hubungan Entitas (Entity Relationship)
- **`users`**: Menyimpan kredensial otentikasi dan peran (`role` enum: admin, guru_bk, guru, orang_tua, siswa).
- **`academic_years`**: Mengatur batasan periode aktif pembelajaran.
- **`teachers`**: Berelasi `belongsTo` dengan `users` (sebagai Guru Mapel atau Wali Kelas).
- **`classrooms`**: Berelasi `belongsTo` dengan `academic_years` dan `teachers` (wali kelas).
- **`students`**: Berelasi `belongsTo` dengan `users`. Terhubung dengan `classrooms` melalui tabel pivot `classroom_student`.
- **`guardians`**: Berelasi `belongsTo` dengan `users`. Terhubung dengan `students` melalui tabel pivot `guardian_student` dengan kolom tambahan `relationship` (ayah, ibu, dll.).
- **`individual_counselings`**: Mencatat sesi konseling individual (`student_id`, `counselor_id` / `users`).
- **`group_counselings`**: Mencatat sesi konseling kelompok (`counselor_id` / `users`). Terhubung ke partisipan melalui tabel pivot `group_counseling_participants` dengan tambahan kolom `notes`.
- **`homeroom_consultations`**: Sesi konsultasi dengan Wali Kelas (`teacher_id`, `student_id`, `counselor_id`).
- **`subject_teacher_consultations`**: Sesi konsultasi dengan Guru Mapel (`teacher_id`, `student_id`, `counselor_id`).
- **`parent_consultations`**: Sesi konsultasi dengan Orang Tua (`guardian_id`, `student_id`, `counselor_id`).
- **`counseling_requests`**: Permohonan jadwal konseling (`student_id`, `counselor_id`, dll.).

---

## 5. Pengujian & Keamanan Kode
- **Otentikasi & Otorisasi**: Proteksi route ketat menggunakan middleware `auth` dan middleware kustom `role` (Admin, Guru BK, dll.).
- **Validasi Data**: Validasi terpusat di Form Request (`Store...Request` dan `Update...Request`) untuk mencegah data kotor.
- **Automated Testing Suite**: Dilengkapi 12 file unit/feature test komprehensif berbasis **PHPUnit** di folder `tests/Feature` yang mencakup happy path, validation error, dan restriction/authorization check untuk semua controller utama. Terdiri dari **118 pengujian (339 assertions) yang lulus 100%**.
- **Penataan Kode**: Menggunakan **Laravel Pint** untuk menjamin konsistensi gaya penulisan kode PHP.

---

## 6. Peta Jalan Pengembangan Selanjutnya (Next Roadmap)

### 🟢 Phase 5: Counseling Requests Portal (100% Selesai & Teruji)
*Sistem permohonan konseling dari siswa dan orang tua:*
- **Permohonan Konseling**: Siswa dan Orang Tua dapat mengajukan permohonan konseling ke Guru BK melalui portal masing-masing.
- **Manajemen Permohonan Guru BK**: Guru BK dapat melihat, menyetujui (dengan otomatis membuat sesi konseling individual terjadwal), atau menolak permohonan yang masuk.
- **Alur Otomatis**: Persetujuan permohonan secara otomatis mengonversi menjadi catatan konseling individual baru (`IndividualCounseling`).

### 🟢 Phase 6: Multi-Role Portals & Custom Sidebar (100% Selesai & Teruji)
*Kustomisasi penuh portal fungsional untuk peran di luar Admin dan Guru BK:*
- **Portal Siswa**: Riwayat konseling individual (read-only), riwayat konseling kelompok beserta catatan khusus per siswa, dashboard dengan statistik dan sesi terbaru.
- **Portal Orang Tua**: Daftar anak yang terhubung beserta statistik konseling, riwayat konseling per anak (read-only), riwayat konsultasi orang tua dengan Guru BK, dashboard dengan card anak dan konsultasi terbaru.
- **Portal Guru (Non-BK / Wali Kelas)**: Riwayat konsultasi wali kelas (read-only), riwayat konsultasi guru mapel (read-only), daftar kelas perwalian beserta siswa, dashboard dengan statistik dan konsultasi terbaru.
- **Sidebar Dinamis**: Navigasi sidebar lengkap dan kontekstual untuk setiap peran pengguna.

---

### 🟡 Tampilan Antarmuka (UI/UX) (95% Selesai)
- **Tema & Gaya**: Menggunakan template modern premium **Wowdash Tailwind CSS Admin Dashboard**.
- **Aset & Ikon**: Menggunakan pustaka Iconify dan Remix Icons untuk visual yang modern dan premium.
- **Mode Gelap**: Didukung penuh oleh gaya dark mode Tailwind.
- **Menu Navigasi**: Sidebar dan header dinamis sudah diatur berdasarkan peran pengguna — **seluruh 5 peran sudah memiliki menu navigasi fungsional**.
- **Dashboard Widget**: Menampilkan statistik real-time yang akurat sesuai dengan peran masing-masing pengguna, dilengkapi dengan daftar aktivitas terbaru dan link navigasi cepat.

---

## 4. Struktur Database & Hubungan Entitas (Entity Relationship)
- **`users`**: Menyimpan kredensial otentikasi dan peran (`role` enum: admin, guru_bk, guru, orang_tua, siswa).
- **`academic_years`**: Mengatur batasan periode aktif pembelajaran.
- **`teachers`**: Berelasi `belongsTo` dengan `users` (sebagai Guru Mapel atau Wali Kelas).
- **`classrooms`**: Berelasi `belongsTo` dengan `academic_years` dan `teachers` (wali kelas).
- **`students`**: Berelasi `belongsTo` dengan `users`. Terhubung dengan `classrooms` melalui tabel pivot `classroom_student`.
- **`guardians`**: Berelasi `belongsTo` dengan `users`. Terhubung dengan `students` melalui tabel pivot `guardian_student` dengan kolom tambahan `relationship` (ayah, ibu, dll.).
- **`individual_counselings`**: Mencatat sesi konseling individual (`student_id`, `counselor_id` / `users`).
- **`group_counselings`**: Mencatat sesi konseling kelompok (`counselor_id` / `users`). Terhubung ke partisipan melalui tabel pivot `group_counseling_participants` dengan tambahan kolom `notes`.
- **`homeroom_consultations`**: Sesi konsultasi dengan Wali Kelas (`teacher_id`, `student_id`, `counselor_id`).
- **`subject_teacher_consultations`**: Sesi konsultasi dengan Guru Mapel (`teacher_id`, `student_id`, `counselor_id`).
- **`parent_consultations`**: Sesi konsultasi dengan Orang Tua (`guardian_id`, `student_id`, `counselor_id`).
- **`counseling_requests`**: Permohonan jadwal konseling (`student_id`, `counselor_id`, dll.).

---

## 5. Pengujian & Keamanan Kode
- **Otentikasi & Otorisasi**: Proteksi route ketat menggunakan middleware `auth` dan middleware kustom `role` (Admin, Guru BK, Guru, Orang Tua, Siswa).
- **Validasi Data**: Validasi terpusat di Form Request (`Store...Request` dan `Update...Request`) untuk mencegah data kotor.
- **Automated Testing Suite**: Dilengkapi 15 file unit/feature test komprehensif berbasis **PHPUnit** di folder `tests/Feature` yang mencakup happy path, validation error, dan restriction/authorization check untuk semua controller utama. Terdiri dari **152 pengujian (416 assertions) yang lulus 100%**.
- **Penataan Kode**: Menggunakan **Laravel Pint** untuk menjamin konsistensi gaya penulisan kode PHP.

---

## 6. Peta Jalan Pengembangan Selanjutnya (Next Roadmap)

### 🔴 Phase 7: Visual Analytics & ApexCharts (Pending / Rencana)
*Analitik data visual untuk Guru BK & Kepala Sekolah:*
- Memanfaatkan pustaka **ApexCharts** (yang sudah terpasang) di dashboard Guru BK untuk menampilkan grafik statistik tren kategori masalah terbanyak (belajar, karir, pribadi, sosial) setiap bulan/semester.

