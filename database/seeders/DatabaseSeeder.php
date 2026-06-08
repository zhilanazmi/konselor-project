<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\AcademicYear;
use App\Models\Classroom;
use App\Models\CounselingRequest;
use App\Models\GroupCounseling;
use App\Models\Guardian;
use App\Models\GuruBkJournal;
use App\Models\HomeroomConsultation;
use App\Models\IndividualCounseling;
use App\Models\ParentConsultation;
use App\Models\Student;
use App\Models\SubjectTeacherConsultation;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Admin
        $admin = User::factory()->create([
            'name' => 'Administrator',
            'email' => 'admin@konselorkita.test',
            'password' => Hash::make('password'),
            'role' => UserRole::Admin,
        ]);

        // 2. Guru BK
        $guruBk = User::factory()->create([
            'name' => 'Ibu Siti Nurhaliza',
            'email' => 'gurubk@konselorkita.test',
            'password' => Hash::make('password'),
            'role' => UserRole::GuruBk,
        ]);

        // 3. Tahun Ajaran Aktif
        $activeYear = AcademicYear::factory()->active()->create([
            'name' => '2025/2026',
            'start_date' => '2025-07-01',
            'end_date' => '2026-06-30',
        ]);

        // 4. Guru (Wali Kelas & Guru Mapel)
        $teachers = collect();

        $teacherData = [
            ['name' => 'Pak Ahmad Dahlan', 'subject' => 'Matematika'],
            ['name' => 'Ibu Kartini', 'subject' => 'Bahasa Indonesia'],
            ['name' => 'Pak Soekarno', 'subject' => 'IPS'],
            ['name' => 'Ibu Megawati', 'subject' => 'IPA'],
            ['name' => 'Pak Habibie', 'subject' => 'Informatika'],
            ['name' => 'Ibu Dewi Sartika', 'subject' => 'Bahasa Inggris'],
        ];

        foreach ($teacherData as $data) {
            $user = User::factory()->create([
                'name' => $data['name'],
                'email' => strtolower(str_replace(' ', '.', $data['name'])).'@konselorkita.test',
                'password' => Hash::make('password'),
                'role' => UserRole::Guru,
            ]);

            $teacher = Teacher::factory()->create([
                'user_id' => $user->id,
                'full_name' => $data['name'],
                'subject' => $data['subject'],
            ]);

            $teachers->push($teacher);
        }

        // 5. Kelas (6 kelas: VII-A, VII-B, VIII-A, VIII-B, IX-A, IX-B)
        $classroomConfig = [
            ['name' => 'VII-A', 'grade' => '7', 'teacher_index' => 0],
            ['name' => 'VII-B', 'grade' => '7', 'teacher_index' => 1],
            ['name' => 'VIII-A', 'grade' => '8', 'teacher_index' => 2],
            ['name' => 'VIII-B', 'grade' => '8', 'teacher_index' => 3],
            ['name' => 'IX-A', 'grade' => '9', 'teacher_index' => 4],
            ['name' => 'IX-B', 'grade' => '9', 'teacher_index' => 5],
        ];

        $classrooms = collect();
        foreach ($classroomConfig as $config) {
            $classroom = Classroom::factory()->create([
                'academic_year_id' => $activeYear->id,
                'homeroom_teacher_id' => $teachers[$config['teacher_index']]->id,
                'name' => $config['name'],
                'grade' => $config['grade'],
            ]);
            $classrooms->push($classroom);
        }

        // 6. Siswa (30 siswa, 5 per kelas)
        $students = collect();
        foreach ($classrooms as $classroom) {
            for ($i = 0; $i < 5; $i++) {
                $student = Student::factory()->create([
                    'full_name' => fake()->name(),
                ]);
                $student->user->update(['name' => $student->full_name]);
                $classroom->students()->attach($student->id);
                $students->push($student);
            }
        }

        // 7. Orang Tua (1 orang tua per siswa)
        foreach ($students as $student) {
            $guardian = Guardian::factory()->create([
                'full_name' => fake()->name(),
            ]);
            $guardian->user->update(['name' => $guardian->full_name]);
            $guardian->students()->attach($student->id, [
                'relationship' => fake()->randomElement(['ayah', 'ibu']),
            ]);
        }

        // 8. Permohonan Konseling (Counseling Requests)
        $counselorUser = User::where('role', UserRole::GuruBk)->first();
        if ($counselorUser && $students->isNotEmpty()) {
            $randomStudents = $students->random(min(5, $students->count()));
            foreach ($randomStudents as $student) {
                CounselingRequest::factory()->create([
                    'student_id' => $student->id,
                    'counselor_id' => $counselorUser->id,
                ]);
            }
        }

        // 9. Konseling Individu (Individual Counseling)
        if ($counselorUser && $students->isNotEmpty()) {
            $individualCounselingData = [
                [
                    'category' => 'belajar',
                    'topic' => 'Mengatasi Kemalasan Belajar',
                    'desc' => 'Siswa merasa bosan dan malas belajar di kelas.',
                    'status' => 'completed',
                    'result' => 'Siswa berjanji membuat jadwal belajar harian.',
                    'follow_up' => 'Pantau nilai ujian tengah semester.',
                ],
                [
                    'category' => 'sosial',
                    'topic' => 'Pertikaian dengan Teman Sebaya',
                    'desc' => 'Terjadi selisih paham dengan teman sebangku.',
                    'status' => 'completed',
                    'result' => 'Kedua pihak saling memaafkan dan berdamai.',
                    'follow_up' => 'Pastikan hubungan pertemanan tetap harmonis.',
                ],
                [
                    'category' => 'pribadi',
                    'topic' => 'Kecemasan Menjelang Ujian',
                    'desc' => 'Siswa merasa sangat cemas dan tidak nafsu makan menjelang ujian.',
                    'status' => 'followed_up',
                    'result' => 'Siswa diajarkan teknik relaksasi pernapasan.',
                    'follow_up' => 'Konseling tindak lanjut minggu depan.',
                ],
                [
                    'category' => 'karir',
                    'topic' => 'Kebingungan Memilih Jurusan SMA/SMK',
                    'desc' => 'Siswa kelas IX masih bingung antara melanjutkan ke SMA atau SMK.',
                    'status' => 'ongoing',
                    'result' => 'Siswa diberikan gambaran perbedaan kurikulum SMA dan SMK.',
                    'follow_up' => 'Mengisi kuesioner minat bakat.',
                ],
                [
                    'category' => 'belajar',
                    'topic' => 'Kesulitan Fokus saat Pelajaran Matematika',
                    'desc' => 'Siswa merasa kesulitan mengikuti pelajaran Matematika dan sering mengantuk.',
                    'status' => 'scheduled',
                    'result' => null,
                    'follow_up' => null,
                ],
            ];

            foreach ($individualCounselingData as $data) {
                IndividualCounseling::factory()->create([
                    'academic_year_id' => $activeYear->id,
                    'counselor_id' => $counselorUser->id,
                    'student_id' => $students->random()->id,
                    'category' => $data['category'],
                    'problem_description' => $data['desc'],
                    'status' => $data['status'],
                    'scheduled_at' => fake()->dateTimeBetween('-1 week', '+2 weeks'),
                ]);
            }
        }

        // 10. Konseling Kelompok (Group Counseling)
        if ($counselorUser && $students->isNotEmpty()) {
            $groupCounselingData = [
                [
                    'topic' => 'Membangun Kebiasaan Membaca (Literasi)',
                    'desc' => 'Mengajak siswa kelompok untuk membangun budaya membaca buku non-pelajaran.',
                    'method' => 'Diskusi kelompok interaktif',
                    'status' => 'completed',
                    'result' => 'Siswa sepakat untuk membaca 1 buku per bulan dan mendiskusikannya.',
                    'evaluation' => 'Peserta aktif memberikan pendapat.',
                ],
                [
                    'topic' => 'Bahaya Bullying di Lingkungan Sekolah',
                    'desc' => 'Sosialisasi dan komitmen bersama untuk mencegah segala bentuk perundungan.',
                    'method' => 'Sosiodrama dan diskusi',
                    'status' => 'completed',
                    'result' => 'Tercapainya deklarasi anti-bullying di kalangan peserta.',
                    'evaluation' => 'Siswa memahami bentuk-bentuk bullying yang harus dihindari.',
                ],
                [
                    'topic' => 'Manajemen Waktu yang Efektif',
                    'desc' => 'Pelatihan mengatur jadwal antara belajar, membantu orang tua, dan bermain.',
                    'method' => 'Self-management training',
                    'status' => 'scheduled',
                    'result' => null,
                    'evaluation' => null,
                ],
            ];

            foreach ($groupCounselingData as $data) {
                $counseling = GroupCounseling::factory()->create([
                    'academic_year_id' => $activeYear->id,
                    'counselor_id' => $counselorUser->id,
                    'topic' => $data['topic'],
                    'description' => $data['desc'],
                    'method' => $data['method'],
                    'status' => $data['status'],
                    'result' => $data['result'],
                    'evaluation' => $data['evaluation'],
                    'scheduled_at' => fake()->dateTimeBetween('-1 week', '+2 weeks'),
                ]);

                // Attach 3-5 random students
                $participants = $students->random(rand(3, 5));
                foreach ($participants as $student) {
                    $counseling->participants()->attach($student->id, [
                        'notes' => fake()->randomElement(['Sangat aktif', 'Cukup aktif', 'Perlu dorongan untuk berbicara', 'Menyimak dengan baik']),
                    ]);
                }
            }
        }

        // 11. Konsultasi Orang Tua (Parent Consultation)
        if ($counselorUser && $students->isNotEmpty()) {
            $parentConsultationData = [
                [
                    'topic' => 'Penurunan Prestasi Belajar Anak',
                    'status' => 'completed',
                    'requested_by' => 'guru_bk',
                    'result' => 'Orang tua setuju membatasi penggunaan gawai (gadget) di rumah.',
                    'agreement' => 'Orang tua memantau waktu belajar anak minimal 1 jam sehari.',
                ],
                [
                    'topic' => 'Ketidakhadiran Tanpa Keterangan (Alpa) Berulang',
                    'status' => 'completed',
                    'requested_by' => 'guru_bk',
                    'result' => 'Orang tua menjelaskan anak ada masalah keluarga sehingga terganggu sekolahnya.',
                    'agreement' => 'Anak akan didorong untuk tetap masuk sekolah, dibantu oleh Wali Kelas.',
                ],
                [
                    'topic' => 'Perilaku Anak yang Menjadi Pendiam di Rumah',
                    'status' => 'scheduled',
                    'requested_by' => 'orang_tua',
                    'result' => null,
                    'agreement' => null,
                ],
            ];

            foreach ($parentConsultationData as $data) {
                // Find a student that has a guardian
                $student = $students->random();
                $guardian = $student->guardians()->first();

                if ($guardian) {
                    ParentConsultation::factory()->create([
                        'academic_year_id' => $activeYear->id,
                        'counselor_id' => $counselorUser->id,
                        'student_id' => $student->id,
                        'guardian_id' => $guardian->id,
                        'topic' => $data['topic'],
                        'status' => $data['status'],
                        'requested_by' => $data['requested_by'],
                        'result' => $data['result'],
                        'agreement' => $data['agreement'],
                        'scheduled_at' => fake()->dateTimeBetween('-1 week', '+2 weeks'),
                    ]);
                }
            }
        }

        // 12. Konsultasi Wali Kelas (Homeroom Consultation)
        if ($counselorUser && $teachers->isNotEmpty() && $students->isNotEmpty()) {
            $homeroomConsultationData = [
                [
                    'topic' => 'Kondisi Psikologis Siswa yang Mengalami Broken Home',
                    'recommendation' => 'Guru BK memberikan konseling individual secara berkala. Wali kelas memberikan kelonggaran tugas jika diperlukan.',
                    'follow_up' => 'Pantau kestabilan emosi siswa di kelas.',
                ],
                [
                    'topic' => 'Siswa yang Sering Terlambat Masuk Jam Pertama',
                    'recommendation' => 'Wali kelas memanggil siswa secara persuasif. Guru BK merencanakan kunjungan rumah (home visit).',
                    'follow_up' => 'Kunjungan rumah dijadwalkan akhir pekan ini.',
                ],
            ];

            foreach ($homeroomConsultationData as $data) {
                $student = $students->random();
                $teacher = $teachers->random();

                HomeroomConsultation::factory()->create([
                    'academic_year_id' => $activeYear->id,
                    'counselor_id' => $counselorUser->id,
                    'student_id' => $student->id,
                    'teacher_id' => $teacher->id,
                    'topic' => $data['topic'],
                    'recommendation' => $data['recommendation'],
                    'follow_up' => $data['follow_up'],
                    'consultation_date' => fake()->dateTimeBetween('-1 month', 'now')->format('Y-m-d'),
                ]);
            }
        }

        // 13. Konsultasi Guru Mata Pelajaran (Subject Teacher Consultation)
        if ($counselorUser && $teachers->isNotEmpty() && $students->isNotEmpty()) {
            $subjectConsultationData = [
                [
                    'subject' => 'Matematika',
                    'topic' => 'Hampir Seluruh Nilai Harian Siswa di Bawah KKM',
                    'recommendation' => 'Guru mapel memberikan bimbingan remedial khusus. Guru BK meneliti gaya belajar siswa tersebut.',
                    'follow_up' => 'Remedial dijadwalkan minggu depan.',
                ],
                [
                    'subject' => 'Bahasa Inggris',
                    'topic' => 'Siswa Selalu Menolak Bicara Saat Praktik Speaking',
                    'recommendation' => 'Guru mapel tidak memaksakan di depan umum, melainkan melakukan tes speaking individu. Guru BK melatih kepercayaan diri siswa.',
                    'follow_up' => 'Latihan speaking berpasangan kecil.',
                ],
            ];

            foreach ($subjectConsultationData as $data) {
                $student = $students->random();
                $teacher = $teachers->random();

                SubjectTeacherConsultation::factory()->create([
                    'academic_year_id' => $activeYear->id,
                    'counselor_id' => $counselorUser->id,
                    'student_id' => $student->id,
                    'teacher_id' => $teacher->id,
                    'subject_name' => $data['subject'],
                    'topic' => $data['topic'],
                    'recommendation' => $data['recommendation'],
                    'follow_up' => $data['follow_up'],
                    'consultation_date' => fake()->dateTimeBetween('-1 month', 'now')->format('Y-m-d'),
                ]);
            }
        }

        // 14. Jurnal Kegiatan Guru BK (Guru BK Journal)
        if ($counselorUser) {
            $journalData = [
                [
                    'type' => 'layanan_dasar',
                    'title' => 'Pemberian Layanan Informasi Klasikal Kelas VII',
                    'desc' => 'Memberikan pemahaman dasar mengenai pentingnya mematuhi tata tertib sekolah.',
                    'target' => 'Siswa Kelas VII-A & VII-B',
                    'loc' => 'Ruang Kelas VII-A',
                    'duration' => 80,
                    'notes' => 'Siswa menyimak presentasi dengan baik dan aktif bertanya.',
                ],
                [
                    'type' => 'layanan_responsif',
                    'title' => 'Konseling Individu Kasus Bolos Sekolah',
                    'desc' => 'Melakukan pemanggilan siswa yang dilaporkan sering membolos pada jam pelajaran terakhir.',
                    'target' => 'Siswa Inisial R',
                    'loc' => 'Ruang BK',
                    'duration' => 45,
                    'notes' => 'Siswa mengakui kesalahannya dan berjanji tidak mengulangi.',
                ],
                [
                    'type' => 'layanan_perencanaan',
                    'title' => 'Bimbingan Kelompok tentang Orientasi Pilihan Karir',
                    'desc' => 'Diskusi kelompok mengenai minat bakat siswa untuk melanjutkan studi.',
                    'target' => 'Kelompok Siswa Kelas IX',
                    'loc' => 'Ruang BK',
                    'duration' => 60,
                    'notes' => 'Siswa mendapat pemahaman dasar perbedaan SMA, SMK, dan MA.',
                ],
                [
                    'type' => 'dukungan_sistem',
                    'title' => 'Mengikuti Rapat Koordinasi dengan Komite Sekolah',
                    'desc' => 'Menghadiri rapat koordinasi evaluasi program bimbingan konseling semester genap.',
                    'target' => 'Komite Sekolah & Staff',
                    'loc' => 'Ruang Rapat Sekolah',
                    'duration' => 120,
                    'notes' => 'Dukungan anggaran untuk program home visit disetujui.',
                ],
                [
                    'type' => 'layanan_dasar',
                    'title' => 'Penyusunan Angket Kebutuhan Siswa (Need Assessment)',
                    'desc' => 'Menyusun angket analisis kebutuhan siswa untuk program BK tahun ajaran berikutnya.',
                    'target' => 'Seluruh Siswa',
                    'loc' => 'Ruang Kerja BK',
                    'duration' => 90,
                    'notes' => 'Angket siap digandakan dan disebarkan.',
                ],
            ];

            foreach ($journalData as $data) {
                GuruBkJournal::factory()->create([
                    'academic_year_id' => $activeYear->id,
                    'counselor_id' => $counselorUser->id,
                    'activity_type' => $data['type'],
                    'title' => $data['title'],
                    'description' => $data['desc'],
                    'target_group' => $data['target'],
                    'location' => $data['loc'],
                    'duration_minutes' => $data['duration'],
                    'notes' => $data['notes'],
                    'date' => fake()->dateTimeBetween('-1 month', 'now')->format('Y-m-d'),
                ]);
            }
        }
    }
}
