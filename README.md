# 🎓 Sistem Manajemen Bimbingan Konseling Sekolah

Aplikasi web untuk mengelola layanan bimbingan dan konseling di sekolah secara digital, lengkap dengan dokumentasi foto, laporan PDF, dan sistem reminder otomatis.

## ✨ Fitur Utama

### 📋 Jenis Layanan Bimbingan
1. **Bimbingan Individu** - Konseling one-on-one dengan siswa
2. **Bimbingan Kelompok** - Konseling untuk kelompok kecil (3-8 siswa)
3. **Bimbingan Klasikal** - Bimbingan untuk satu kelas (15-25 siswa)
4. **Bimbingan Kelas Besar** - Bimbingan untuk multiple kelas (30+ siswa)
5. **Konsultasi Wali Kelas** - Koordinasi dengan wali kelas
6. **Konsultasi Orang Tua** - Pertemuan dengan orang tua/wali
7. **Konsultasi Pihak Luar** - Rujukan ke psikolog/terapis eksternal

### 🎯 Fitur Lengkap
- ✅ Multi-student selection untuk bimbingan kelompok
- ✅ Upload foto dokumentasi (multiple files, JPG/PNG, max 5MB)
- ✅ Cetak laporan PDF dengan 3 tanda tangan (Guru BK, Wali Kelas, Kepala Sekolah)
- ✅ Filter & search berdasarkan topik, tahun ajaran, status, jenis layanan
- ✅ Dashboard dengan statistik lengkap
- ✅ Sistem reminder otomatis untuk sesi yang akan datang
- ✅ Manajemen tahun ajaran
- ✅ Role-based access (Admin, Guru BK, Wali Kelas)

## 🚀 Quick Start

### Persyaratan Sistem
- PHP >= 8.2
- MySQL >= 5.7
- Composer
- Node.js & NPM (untuk asset compilation)

### Instalasi

```bash
# 1. Clone repository
git clone <repository-url>
cd apk-konselor

# 2. Install dependencies
composer install
npm install

# 3. Setup environment
cp .env.example .env
php artisan key:generate

# 4. Konfigurasi database di .env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=konselor_db
DB_USERNAME=root
DB_PASSWORD=

# 5. Jalankan migrasi
php artisan migrate

# 6. Create storage link
php artisan storage:link

# 7. (Optional) Seed data demo
php artisan db:seed --class=CounselingDemoSeeder

# 8. Compile assets
npm run build

# 9. Jalankan aplikasi
php artisan serve
```

Akses aplikasi di: `http://localhost:8000`

## 👤 Default Users

Setelah seeding, Anda bisa login dengan:

**Admin**
- Email: admin@example.com
- Password: password

**Guru BK**
- Email: gurubk@example.com
- Password: password

## 📖 Panduan Penggunaan

### Untuk Guru BK

#### 1. Membuat Sesi Bimbingan Individu
1. Login sebagai Guru BK
2. Klik menu "Bimbingan Individu"
3. Klik tombol "Tambah Sesi"
4. Isi form:
   - Pilih tahun ajaran
   - Pilih siswa
   - Tentukan jadwal
   - Pilih kategori (Akademik/Sosial/Pribadi/Karir)
   - Isi deskripsi masalah, pendekatan, hasil
   - Isi evaluasi dan tindak lanjut
5. Upload foto dokumentasi (optional)
6. Klik "Simpan"

#### 2. Membuat Bimbingan Kelompok
1. Klik menu "Bimbingan Kelompok"
2. Klik "Tambah Sesi"
3. Pilih jenis layanan:
   - Bimbingan Kelompok (3-8 siswa)
   - Bimbingan Klasikal (15-25 siswa)
   - Bimbingan Kelas Besar (30+ siswa)
4. Pilih multiple siswa sebagai peserta
5. Isi topik, metode, dan detail lainnya
6. Upload foto dokumentasi
7. Klik "Simpan"

#### 3. Menambah Peserta ke Bimbingan Kelompok
1. Buka detail bimbingan kelompok
2. Scroll ke bagian "Daftar Peserta"
3. Pilih siswa dari dropdown
4. Klik "Tambah Peserta"
5. (Optional) Tambahkan catatan untuk peserta

#### 4. Cetak Laporan PDF
1. Buka detail sesi bimbingan
2. Klik tombol "Cetak PDF"
3. PDF akan terbuka di tab baru
4. Gunakan Ctrl+P untuk print atau save as PDF

#### 5. Melihat Statistik Dashboard
1. Setelah login, Anda akan melihat dashboard
2. Dashboard menampilkan:
   - Total siswa
   - Jumlah setiap jenis bimbingan
   - Status sesi (scheduled, ongoing, completed)
   - 10 aktivitas terbaru

### Untuk Admin

#### 1. Mengatur Data Kepala Sekolah
1. Login sebagai Admin
2. Klik menu "Pengaturan"
3. Isi:
   - Nama Lengkap Kepala Sekolah
   - NIP Kepala Sekolah
4. Klik "Simpan"

**Penting**: Data ini akan muncul di tanda tangan PDF!

#### 2. Mengelola Tahun Ajaran
1. Klik menu "Tahun Ajaran"
2. Klik "Tambah Tahun Ajaran"
3. Isi nama (contoh: 2025/2026)
4. Tentukan tanggal mulai dan selesai
5. Centang "Aktif" untuk tahun ajaran yang sedang berjalan
6. Klik "Simpan"

**Catatan**: Hanya boleh ada 1 tahun ajaran aktif!

## 🔧 Fitur Tambahan

### Sistem Reminder Otomatis

Aplikasi dapat mengirim reminder otomatis untuk sesi bimbingan yang akan datang.

#### Setup Cron Job (Production)

Tambahkan di crontab server:
```bash
* * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1
```

Kemudian tambahkan di `routes/console.php`:
```php
use Illuminate\Support\Facades\Schedule;

Schedule::command('counseling:send-reminders')->daily();
```

#### Manual Testing
```bash
php artisan counseling:send-reminders
```

### Helper Functions

Aplikasi menyediakan helper functions untuk memudahkan development:

```php
// Format tanggal Indonesia
format_date_indonesia($date); // 28 Mei 2026
format_datetime_indonesia($datetime); // 28 Mei 2026, 14:30

// Badge status
get_status_badge_class('completed'); // CSS class
get_status_label('completed'); // "Selesai"

// Badge jenis layanan
get_service_type_badge_class('group'); // CSS class
get_service_type_label('group'); // "Bimbingan Kelompok"
```

## 📁 Struktur Folder

```
apk-konselor/
├── app/
│   ├── Console/Commands/      # Artisan commands
│   ├── Enums/                 # Enum classes
│   ├── Helpers/               # Helper functions
│   ├── Http/
│   │   ├── Controllers/       # Controllers
│   │   ├── Middleware/        # Middleware
│   │   └── Requests/          # Form Requests
│   ├── Models/                # Eloquent models
│   ├── Notifications/         # Notifications
│   └── Services/              # Service classes
├── database/
│   ├── migrations/            # Database migrations
│   └── seeders/               # Database seeders
├── resources/
│   └── views/                 # Blade templates
├── routes/
│   ├── web.php               # Web routes
│   └── console.php           # Console routes
└── storage/
    └── app/public/
        └── counseling-documents/  # Uploaded photos
```

## 🎨 Teknologi

- **Backend**: Laravel 11
- **Frontend**: Blade Templates + Tailwind CSS
- **Database**: MySQL
- **Icons**: Iconify
- **PDF**: Browser Native Print

## 🔒 Security

- CSRF Protection
- XSS Protection
- SQL Injection Prevention
- File Upload Validation
- Role-based Access Control

## 📊 Database Schema

### Tabel Utama
- `users` - Data pengguna (Admin, Guru BK, Wali Kelas)
- `students` - Data siswa
- `teachers` - Data guru
- `guardians` - Data orang tua/wali
- `academic_years` - Tahun ajaran
- `classrooms` - Data kelas
- `individual_counselings` - Bimbingan individu
- `group_counselings` - Bimbingan kelompok
- `homeroom_consultations` - Konsultasi wali kelas
- `parent_consultations` - Konsultasi orang tua
- `external_consultations` - Konsultasi pihak luar
- `counseling_documents` - Foto dokumentasi
- `school_settings` - Pengaturan sekolah

## 🐛 Troubleshooting

### Error: "Storage link not found"
```bash
php artisan storage:link
```

### Error: "Class not found"
```bash
composer dump-autoload
```

### Error: "View not found"
```bash
php artisan view:clear
php artisan config:clear
```

### Error: "Route not found"
```bash
php artisan route:clear
php artisan route:cache
```

### Upload foto tidak berfungsi
1. Pastikan folder `storage/app/public` writable
2. Pastikan storage link sudah dibuat
3. Check file size (max 5MB)
4. Check file type (hanya JPG/PNG)

## 📞 Support

Jika ada pertanyaan atau issue, silakan buat issue di repository atau hubungi developer.

## 📄 License

Proprietary - All rights reserved

---

**Developed with ❤️ for Indonesian Schools**
