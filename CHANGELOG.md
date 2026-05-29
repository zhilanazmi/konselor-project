# Changelog - Sistem Manajemen Bimbingan Konseling

## [1.0.0] - 2026-05-28

### ✨ Fitur Utama yang Telah Diimplementasikan

#### 1. **Sistem Bimbingan Konseling Lengkap**
- ✅ Bimbingan Individu (Individual Counseling)
- ✅ Bimbingan Kelompok (Group Counseling)
- ✅ Bimbingan Klasikal (Classroom Guidance)
- ✅ Bimbingan Kelas Besar (Large Class Guidance)
- ✅ Konsultasi Wali Kelas (Homeroom Consultation)
- ✅ Konsultasi Orang Tua (Parent Consultation)
- ✅ Konsultasi Pihak Luar (External Consultation)

#### 2. **Fitur Dokumentasi**
- Upload multiple foto dokumentasi (JPG/PNG, max 5MB per file)
- Preview dan delete foto dokumentasi
- Penyimpanan terorganisir di storage

#### 3. **Sistem Laporan PDF**
- Template PDF profesional untuk semua jenis bimbingan
- 3 kolom tanda tangan: Guru BK, Wali Kelas, Kepala Sekolah
- Menampilkan Nama Lengkap dan NIP
- Format laporan sesuai standar BK

#### 4. **Multi-Student Selection**
- Relasi many-to-many untuk Group Counseling
- Tambah/hapus peserta secara dinamis
- Catatan individual per peserta

#### 5. **Filter & Search**
- Filter berdasarkan: topik, tahun ajaran, status, jenis layanan
- Badge warna untuk status dan jenis layanan
- Pagination untuk performa optimal

### 🎯 Penambahan Fitur Baru (Hari Ini)

#### 1. **Dashboard dengan Statistik Lengkap**
**File**: `app/Http/Controllers/DashboardController.php`

Dashboard sekarang menampilkan:
- Total siswa
- Jumlah setiap jenis bimbingan per tahun ajaran aktif
- Statistik per status (scheduled, ongoing, completed)
- 10 aktivitas terbaru dengan link langsung
- Informasi tahun ajaran aktif

**Cara Menggunakan**:
```php
// Dashboard otomatis menampilkan statistik untuk Guru BK
// Akses: /dashboard setelah login sebagai Guru BK
```

#### 2. **Seeder untuk Data Demo**
**File**: `database/seeders/CounselingDemoSeeder.php`

Seeder ini membuat data demo untuk:
- 5 Individual Counseling records
- 3 Group Counseling records (Group, Classroom, Large Class)
- 3 Homeroom Consultation records
- 3 Parent Consultation records
- 2 External Consultation records

**Cara Menggunakan**:
```bash
php artisan db:seed --class=CounselingDemoSeeder
```

**Catatan**: Pastikan sudah ada:
- Academic Year aktif
- User dengan role 'guru_bk'
- Minimal 10 Students
- Minimal 1 Teacher
- Minimal 1 Guardian

#### 3. **Helper Functions untuk Format Indonesia**
**File**: `app/Helpers/helpers.php`

Helper functions yang tersedia:
- `format_date_indonesia($date, $format)` - Format tanggal ke Bahasa Indonesia
- `format_datetime_indonesia($datetime)` - Format tanggal dan waktu
- `get_status_badge_class($status)` - CSS class untuk badge status
- `get_status_label($status)` - Label Indonesia untuk status
- `get_service_type_badge_class($serviceType)` - CSS class untuk badge jenis layanan
- `get_service_type_label($serviceType)` - Label Indonesia untuk jenis layanan

**Cara Menggunakan**:
```php
// Di Blade template
{{ format_date_indonesia($counseling->scheduled_at) }}
// Output: 28 Mei 2026

{{ format_datetime_indonesia($counseling->scheduled_at) }}
// Output: 28 Mei 2026, 14:30

<span class="{{ get_status_badge_class($counseling->status) }}">
    {{ get_status_label($counseling->status) }}
</span>

<span class="{{ get_service_type_badge_class($counseling->service_type) }}">
    {{ get_service_type_label($counseling->service_type) }}
</span>
```

#### 4. **Middleware Check Academic Year**
**File**: `app/Http/Middleware/CheckActiveAcademicYear.php`

Middleware ini memastikan ada tahun ajaran aktif sebelum mengakses fitur counseling.

**Cara Menggunakan**:
```php
// Di routes/web.php atau bootstrap/app.php
Route::middleware(['auth', 'check.academic.year'])->group(function () {
    // Routes yang memerlukan academic year aktif
});
```

#### 5. **Notification System untuk Reminder**
**File**: `app/Notifications/CounselingReminderNotification.php`

Notifikasi otomatis untuk mengingatkan Guru BK tentang sesi bimbingan yang akan datang.

**Fitur**:
- Notifikasi database (bisa diperluas ke email/SMS)
- Informasi lengkap: jenis, topik, jadwal, link
- Queue support untuk performa optimal

#### 6. **Command untuk Send Reminders**
**File**: `app/Console/Commands/SendCounselingReminders.php`

Command untuk mengirim reminder otomatis setiap hari.

**Cara Menggunakan**:
```bash
# Manual
php artisan counseling:send-reminders

# Otomatis via Cron (tambahkan di routes/console.php)
Schedule::command('counseling:send-reminders')->daily();
```

**Fitur**:
- Mengirim reminder untuk sesi besok
- Support Individual, Group, dan Parent Consultation
- Menampilkan jumlah reminder yang dikirim

### 📊 Statistik Implementasi

- **Total Controllers**: 12
- **Total Models**: 13
- **Total Migrations**: 20
- **Total Routes**: 57
- **Total Views**: 35+
- **Total Form Requests**: 24
- **Total Notifications**: 1
- **Total Commands**: 1
- **Total Helpers**: 6 functions

### 🔧 Teknologi yang Digunakan

- **Framework**: Laravel 11.51.0
- **PHP**: 8.4.15
- **Database**: MySQL
- **Frontend**: Blade Templates + Tailwind CSS
- **Icons**: Iconify
- **PDF**: Browser Print (native)

### 📝 Catatan Penting

1. **Storage Link**: Pastikan sudah menjalankan `php artisan storage:link`
2. **Permissions**: Folder `storage` dan `bootstrap/cache` harus writable
3. **Academic Year**: Harus ada minimal 1 tahun ajaran aktif
4. **School Settings**: Admin harus mengisi data Kepala Sekolah untuk tanda tangan PDF
5. **Notifications**: Jalankan `php artisan migrate` untuk membuat tabel notifications

### 🚀 Cara Setup

```bash
# 1. Install dependencies
composer install

# 2. Setup environment
cp .env.example .env
php artisan key:generate

# 3. Setup database
php artisan migrate

# 4. Create storage link
php artisan storage:link

# 5. (Optional) Seed demo data
php artisan db:seed --class=CounselingDemoSeeder

# 6. Clear cache
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear

# 7. Format code
vendor/bin/pint
```

### 📖 Dokumentasi API Routes

#### Guru BK Routes (55 routes)
- Individual Counseling: `/guru-bk/individual-counselings/*`
- Group Counseling: `/guru-bk/group-counselings/*`
- Homeroom Consultation: `/guru-bk/homeroom-consultations/*`
- Parent Consultation: `/guru-bk/parent-consultations/*`
- External Consultation: `/guru-bk/external-consultations/*`
- Subject Teacher Consultation: `/guru-bk/subject-teacher-consultations/*`

#### Admin Routes (2 routes)
- School Settings: `/admin/school-settings`

### 🐛 Known Issues

Tidak ada issue yang diketahui saat ini.

### 🔮 Future Enhancements (Rekomendasi)

1. **Export to Excel** - Export laporan ke format Excel
2. **Email Notifications** - Kirim email reminder ke Guru BK
3. **SMS Gateway** - Kirim SMS reminder ke orang tua
4. **Dashboard Charts** - Grafik statistik bimbingan
5. **Student Portal** - Portal untuk siswa request bimbingan
6. **Parent Portal** - Portal untuk orang tua lihat progress anak
7. **Calendar View** - Tampilan kalender untuk jadwal bimbingan
8. **Attendance System** - Sistem absensi untuk Group Counseling
9. **Report Templates** - Template laporan yang bisa dikustomisasi
10. **Backup System** - Sistem backup otomatis database

### 👥 Credits

Developed with ❤️ for Sistem Manajemen Bimbingan Konseling Sekolah

### 📄 License

Proprietary - All rights reserved
