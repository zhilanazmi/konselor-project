# Dokumen Kebutuhan (Requirements)

## Introduction

Fitur ini merupakan pengembangan dan penyesuaian sistem manajemen bimbingan konseling sekolah berbasis Laravel 11. Sistem yang sudah ada memiliki struktur dasar (model User, Classroom, Student, Teacher, Guardian, AcademicYear) beserta controller untuk beberapa jenis layanan konseling. Pengembangan ini berfokus pada empat area utama: (1) penyesuaian manajemen peran pengguna dengan penambahan role Wali Kelas, (2) perluasan fitur sesi bimbingan dengan pemilihan multi-siswa dan jenis layanan baru, (3) penambahan form konsultasi pihak luar dengan teks bebas, serta (4) fitur cetak PDF laporan dengan lembar pengesahan tiga kolom tanda tangan.

---

## Glosarium

- **Sistem**: Aplikasi manajemen bimbingan konseling sekolah berbasis Laravel 11.
- **Admin**: Pengguna dengan akses penuh untuk mengelola data master dan pengguna sistem.
- **Guru_BK**: Guru Bimbingan dan Konseling yang bertanggung jawab mencatat dan mengelola seluruh sesi layanan bimbingan.
- **Siswa**: Peserta didik yang menjadi subjek layanan bimbingan konseling.
- **Wali_Kelas**: Guru yang menjadi wali kelas dan dapat berkonsultasi dengan Guru_BK terkait siswa di kelasnya.
- **Sesi_Bimbingan**: Satu catatan kegiatan layanan bimbingan konseling yang dilakukan oleh Guru_BK.
- **Jenis_Layanan**: Kategori layanan bimbingan yang terdiri dari: Bimbingan Individu, Bimbingan Kelompok, Bimbingan Klasikal, dan Bimbingan Kelas Besar.
- **Konsultasi_Pihak_Luar**: Catatan konsultasi Guru_BK dengan pihak eksternal seperti Orang Tua atau Psikolog yang tidak terdaftar sebagai pengguna sistem.
- **Laporan_Bimbingan**: Dokumen digital yang merangkum satu Sesi_Bimbingan, mencakup kolom evaluasi dan tindak lanjut.
- **Lembar_Pengesahan**: Bagian bawah PDF laporan yang memuat tiga kolom tanda tangan: Guru_BK, Wali_Kelas (Mengetahui), dan Kepala Sekolah (Mengetahui).
- **Kepala_Sekolah**: Pejabat sekolah yang namanya dicantumkan pada Lembar_Pengesahan PDF; tidak harus memiliki akun di sistem.
- **NIP**: Nomor Induk Pegawai, identifikasi unik untuk tenaga pendidik dan kependidikan.
- **Dokumentasi_Foto**: Berkas gambar berformat JPG atau PNG yang diunggah sebagai bukti kegiatan pada sebuah Laporan_Bimbingan.
- **Tahun_Akademik**: Periode akademik aktif yang menjadi konteks pencatatan seluruh sesi bimbingan.

---

## Requirements

### Requirement 1: Manajemen Peran Pengguna

**User Story:** Sebagai Admin, saya ingin mengelola pengguna dengan empat peran yang jelas (Admin, Guru BK, Siswa, Wali Kelas), sehingga setiap pengguna hanya dapat mengakses fitur yang sesuai dengan tanggung jawabnya.

#### Acceptance Criteria

1. THE Sistem SHALL mendukung tepat empat peran pengguna: Admin, Guru_BK, Siswa, dan Wali_Kelas.
2. WHEN Admin membuat atau memperbarui akun pengguna, THE Sistem SHALL mewajibkan pemilihan salah satu dari empat peran yang tersedia.
3. WHILE pengguna dengan peran Wali_Kelas sedang login, THE Sistem SHALL menampilkan hanya menu dan data yang relevan dengan kelas yang diampu.
4. WHILE pengguna dengan peran Siswa sedang login, THE Sistem SHALL menampilkan hanya riwayat layanan bimbingan milik siswa tersebut.
5. WHILE pengguna dengan peran Guru_BK sedang login, THE Sistem SHALL memberikan akses penuh untuk membuat, membaca, memperbarui, dan menghapus seluruh Sesi_Bimbingan.
6. WHILE pengguna dengan peran Admin sedang login, THE Sistem SHALL memberikan akses penuh untuk mengelola data master pengguna, kelas, siswa, guru, dan Tahun_Akademik.
7. IF pengguna yang tidak terautentikasi mencoba mengakses halaman yang dilindungi, THEN THE Sistem SHALL mengalihkan pengguna ke halaman login.
8. IF pengguna yang terautentikasi mencoba mengakses halaman di luar hak aksesnya, THEN THE Sistem SHALL menampilkan halaman error 403 (Forbidden).

---

### Requirement 2: Alur Konsultasi Wali Kelas dengan Guru BK

**User Story:** Sebagai Wali_Kelas, saya ingin dapat mengajukan konsultasi kepada Guru_BK terkait siswa di kelas saya, sehingga permasalahan siswa dapat ditangani secara kolaboratif.

#### Acceptance Criteria

1. WHEN Wali_Kelas mengajukan konsultasi, THE Sistem SHALL menyimpan data konsultasi yang mencakup: Tahun_Akademik, tanggal konsultasi, nama siswa yang dibahas, topik permasalahan, rekomendasi Guru_BK, dan kolom tindak lanjut.
2. WHEN Guru_BK menerima pengajuan konsultasi dari Wali_Kelas, THE Sistem SHALL memungkinkan Guru_BK untuk mengisi kolom rekomendasi dan tindak lanjut pada catatan konsultasi tersebut.
3. THE Sistem SHALL menampilkan daftar konsultasi Wali_Kelas yang dapat difilter berdasarkan Tahun_Akademik dan nama Wali_Kelas.
4. WHEN Wali_Kelas mengakses daftar konsultasi, THE Sistem SHALL menampilkan hanya konsultasi yang diajukan oleh Wali_Kelas yang sedang login; pembatasan ini berlaku hanya pada halaman daftar konsultasi dan tidak berlaku pada ringkasan dashboard atau notifikasi.
5. IF Wali_Kelas mencoba mengakses data konsultasi milik Wali_Kelas lain, THEN THE Sistem SHALL menampilkan halaman error 403 (Forbidden).

---

### Requirement 3: Pemilihan Multi-Siswa pada Sesi Bimbingan

**User Story:** Sebagai Guru_BK, saya ingin dapat memilih lebih dari satu siswa dalam satu sesi bimbingan kelompok, sehingga saya tidak perlu membuat catatan terpisah untuk setiap siswa dalam sesi yang sama.

#### Acceptance Criteria

1. WHEN Guru_BK membuat sesi bimbingan dengan Jenis_Layanan Bimbingan Kelompok, Bimbingan Klasikal, atau Bimbingan Kelas Besar, THE Sistem SHALL menyediakan antarmuka pemilihan lebih dari satu siswa sekaligus.
2. THE Sistem SHALL menyimpan relasi antara satu Sesi_Bimbingan dan banyak Siswa menggunakan tabel pivot (many-to-many).
3. WHEN Guru_BK memilih siswa untuk sesi bimbingan kelompok, THE Sistem SHALL memungkinkan pencarian siswa berdasarkan nama atau NIS.
4. WHEN Guru_BK menampilkan detail sesi bimbingan kelompok, THE Sistem SHALL menampilkan seluruh daftar siswa peserta beserta catatan individual masing-masing siswa jika ada.
5. IF Guru_BK mencoba menyimpan sesi bimbingan kelompok tanpa memilih satu pun siswa, THEN THE Sistem SHALL menampilkan pesan validasi "Minimal satu siswa harus dipilih"; sesi dengan tepat satu siswa tetap dapat disimpan.
6. WHEN Guru_BK membuat sesi Bimbingan Individu, THE Sistem SHALL membatasi pemilihan hanya pada satu siswa.

---

### Requirement 4: Jenis Layanan Bimbingan

**User Story:** Sebagai Guru_BK, saya ingin mencatat sesi bimbingan dengan empat jenis layanan yang berbeda, sehingga laporan kegiatan bimbingan dapat dikategorikan dengan tepat sesuai standar layanan BK.

#### Acceptance Criteria

1. THE Sistem SHALL mendukung tepat empat Jenis_Layanan: Bimbingan Individu, Bimbingan Kelompok, Bimbingan Klasikal, dan Bimbingan Kelas Besar.
2. WHEN Guru_BK membuat Sesi_Bimbingan, THE Sistem SHALL mewajibkan pemilihan salah satu Jenis_Layanan.
3. WHEN Guru_BK memilih Jenis_Layanan Bimbingan Individu, THE Sistem SHALL menampilkan form dengan pemilihan satu siswa; form multi-siswa dapat tetap terlihat namun form satu siswa yang aktif digunakan.
4. WHEN Guru_BK memilih Jenis_Layanan Bimbingan Kelompok, Bimbingan Klasikal, atau Bimbingan Kelas Besar, THE Sistem SHALL menampilkan form dengan pemilihan multi-siswa.
5. THE Sistem SHALL memungkinkan pemfilteran daftar Sesi_Bimbingan berdasarkan Jenis_Layanan.
6. WHEN Guru_BK mencetak laporan, THE Sistem SHALL mencantumkan Jenis_Layanan pada dokumen PDF yang dihasilkan.

---

### Requirement 5: Form Laporan Bimbingan dengan Evaluasi dan Tindak Lanjut

**User Story:** Sebagai Guru_BK, saya ingin setiap laporan bimbingan memiliki kolom Evaluasi dan Tindak Lanjut yang terstruktur, sehingga dokumentasi hasil layanan bimbingan menjadi lengkap dan terstandar.

#### Acceptance Criteria

1. THE Sistem SHALL menyediakan kolom "Evaluasi" pada setiap form Laporan_Bimbingan untuk semua Jenis_Layanan.
2. THE Sistem SHALL menyediakan kolom "Tindak Lanjut" pada setiap form Laporan_Bimbingan, dan kolom ini ditampilkan tepat di bawah kolom "Evaluasi".
3. WHEN Guru_BK menyimpan Laporan_Bimbingan, THE Sistem SHALL menyimpan nilai kolom Evaluasi dan Tindak Lanjut ke basis data hanya ketika Guru_BK secara eksplisit menekan tombol simpan.
4. WHEN Guru_BK menampilkan detail Laporan_Bimbingan, THE Sistem SHALL menampilkan kolom Evaluasi dan Tindak Lanjut secara berurutan dengan Evaluasi di atas Tindak Lanjut.
5. WHERE kolom Evaluasi dan Tindak Lanjut bersifat opsional, THE Sistem SHALL tetap menyimpan Laporan_Bimbingan meskipun kedua kolom tersebut kosong.

---

### Requirement 6: Konsultasi Pihak Luar

**User Story:** Sebagai Guru_BK, saya ingin mencatat konsultasi dengan pihak eksternal seperti Orang Tua atau Psikolog yang tidak terdaftar di sistem, sehingga seluruh interaksi terkait bimbingan siswa terdokumentasi dengan lengkap.

#### Acceptance Criteria

1. THE Sistem SHALL menyediakan form Konsultasi_Pihak_Luar yang terpisah dari form konsultasi internal.
2. THE Sistem SHALL menyediakan kolom teks bebas "Nama Pihak Luar" pada form Konsultasi_Pihak_Luar untuk mencatat nama pihak eksternal (contoh: nama orang tua, nama psikolog).
3. THE Sistem SHALL menyediakan kolom teks bebas "Peran/Hubungan" pada form Konsultasi_Pihak_Luar untuk mencatat peran pihak eksternal (contoh: Orang Tua, Psikolog, Dokter).
4. WHEN Guru_BK menyimpan Konsultasi_Pihak_Luar, THE Sistem SHALL mewajibkan pengisian kolom Nama Pihak Luar, tanggal konsultasi, dan topik konsultasi.
5. THE Sistem SHALL menyediakan kolom Evaluasi dan Tindak Lanjut pada form Konsultasi_Pihak_Luar sesuai standar Laporan_Bimbingan.
6. THE Sistem SHALL menampilkan daftar Konsultasi_Pihak_Luar yang dapat difilter berdasarkan Tahun_Akademik dan nama siswa terkait.

---

### Requirement 7: Dokumentasi Foto pada Laporan

**User Story:** Sebagai Guru_BK, saya ingin dapat mengunggah foto sebagai bukti kegiatan pada setiap laporan bimbingan, sehingga dokumentasi kegiatan menjadi lebih kuat dan dapat diverifikasi.

#### Acceptance Criteria

1. THE Sistem SHALL menyediakan tombol unggah foto pada setiap form Laporan_Bimbingan untuk semua Jenis_Layanan.
2. THE Sistem SHALL menerima hanya berkas dengan format JPG dan PNG untuk unggahan Dokumentasi_Foto.
3. IF Guru_BK mengunggah berkas dengan format selain JPG atau PNG, THEN THE Sistem SHALL menampilkan pesan validasi "Format file tidak didukung. Gunakan JPG atau PNG".
4. THE Sistem SHALL mendukung unggahan lebih dari satu Dokumentasi_Foto per Laporan_Bimbingan.
5. WHEN Guru_BK menampilkan detail Laporan_Bimbingan, THE Sistem SHALL menampilkan seluruh Dokumentasi_Foto yang telah diunggah dalam bentuk pratinjau gambar.
6. WHEN Guru_BK menghapus Laporan_Bimbingan, THE Sistem SHALL menghapus seluruh berkas Dokumentasi_Foto yang terkait dari penyimpanan.
7. IF ukuran berkas Dokumentasi_Foto melebihi 5 MB, THEN THE Sistem SHALL menampilkan pesan validasi "Ukuran file maksimal 5 MB".

---

### Requirement 8: Cetak PDF Laporan dengan Lembar Pengesahan

**User Story:** Sebagai Guru_BK, saya ingin mencetak laporan bimbingan dalam format PDF yang memiliki lembar pengesahan resmi, sehingga laporan dapat digunakan sebagai dokumen formal yang sah.

#### Acceptance Criteria

1. THE Sistem SHALL menyediakan tombol "Cetak PDF" pada halaman detail setiap Laporan_Bimbingan.
2. WHEN Guru_BK menekan tombol "Cetak PDF", THE Sistem SHALL menghasilkan dokumen PDF yang memuat seluruh data Laporan_Bimbingan.
3. THE Sistem SHALL menyertakan Lembar_Pengesahan pada bagian bawah setiap dokumen PDF laporan bimbingan.
4. THE Lembar_Pengesahan SHALL memuat tepat tiga kolom tanda tangan yang tersusun secara horizontal: kolom pertama untuk Guru_BK, kolom kedua untuk Wali_Kelas dengan keterangan "Mengetahui", dan kolom ketiga untuk Kepala Sekolah dengan keterangan "Mengetahui"; jumlah kolom tidak dapat ditambah.
5. THE Sistem SHALL menampilkan Nama Lengkap dan NIP pada setiap kolom tanda tangan di Lembar_Pengesahan.
6. WHEN Guru_BK mencetak laporan, THE Sistem SHALL mengambil data Nama Lengkap dan NIP Guru_BK dari profil pengguna yang sedang login.
7. WHEN Guru_BK mencetak laporan, THE Sistem SHALL mengambil data Nama Lengkap dan NIP Wali_Kelas dari data Wali_Kelas yang terkait dengan kelas siswa pada Sesi_Bimbingan tersebut.
8. THE Sistem SHALL menyediakan pengaturan data Kepala Sekolah (Nama Lengkap dan NIP) yang dapat dikonfigurasi oleh Admin, dan data tersebut digunakan pada seluruh Lembar_Pengesahan PDF.
9. WHEN Guru_BK mencetak laporan Konsultasi_Pihak_Luar, THE Sistem SHALL tetap menyertakan Lembar_Pengesahan dengan tiga kolom tanda tangan yang sama.
10. THE Sistem SHALL menghasilkan PDF dengan orientasi potret (portrait) dan ukuran kertas A4.
