<?php

namespace Database\Seeders;

use App\Enums\CounselingServiceType;
use App\Models\AcademicYear;
use App\Models\ExternalConsultation;
use App\Models\GroupCounseling;
use App\Models\Guardian;
use App\Models\HomeroomConsultation;
use App\Models\IndividualCounseling;
use App\Models\ParentConsultation;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Database\Seeder;

class CounselingDemoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $academicYear = AcademicYear::query()->where('is_active', true)->first();
        $counselor = User::query()->where('role', 'guru_bk')->first();
        $students = Student::query()->limit(10)->get();
        $teacher = Teacher::query()->first();
        $guardian = Guardian::query()->first();

        if (! $academicYear || ! $counselor || $students->isEmpty()) {
            $this->command->warn('Pastikan sudah ada Academic Year aktif, Guru BK, dan Students di database.');

            return;
        }

        // Individual Counseling
        $this->command->info('Creating Individual Counseling records...');
        foreach ($students->take(5) as $student) {
            IndividualCounseling::query()->create([
                'academic_year_id' => $academicYear->id,
                'counselor_id' => $counselor->id,
                'student_id' => $student->id,
                'scheduled_at' => now()->addDays(rand(1, 30)),
                'status' => ['scheduled', 'ongoing', 'completed'][rand(0, 2)],
                'service_type' => CounselingServiceType::Individual,
                'category' => ['Akademik', 'Sosial', 'Pribadi', 'Karir'][rand(0, 3)],
                'problem_description' => 'Siswa mengalami kesulitan dalam '.$this->getRandomProblem(),
                'approach' => 'Pendekatan konseling individual dengan teknik '.$this->getRandomApproach(),
                'result' => 'Siswa menunjukkan perkembangan positif dan mulai memahami permasalahannya.',
                'evaluation' => 'Konseling berjalan dengan baik. Siswa kooperatif dan terbuka dalam menceritakan permasalahannya.',
                'follow_up' => 'Monitoring perkembangan siswa setiap minggu dan konsultasi lanjutan jika diperlukan.',
                'follow_up_plan' => 'Pertemuan follow-up dijadwalkan 2 minggu ke depan.',
            ]);
        }

        // Group Counseling
        $this->command->info('Creating Group Counseling records...');
        $serviceTypes = [CounselingServiceType::Group, CounselingServiceType::Classroom, CounselingServiceType::LargeClass];

        foreach ($serviceTypes as $serviceType) {
            $groupCounseling = GroupCounseling::query()->create([
                'academic_year_id' => $academicYear->id,
                'counselor_id' => $counselor->id,
                'topic' => $this->getRandomTopic($serviceType),
                'description' => 'Kegiatan bimbingan untuk meningkatkan '.$this->getRandomGoal(),
                'method' => 'Metode diskusi kelompok, role play, dan sharing session',
                'scheduled_at' => now()->addDays(rand(1, 30)),
                'status' => ['scheduled', 'ongoing', 'completed'][rand(0, 2)],
                'service_type' => $serviceType,
                'result' => 'Peserta aktif mengikuti kegiatan dan menunjukkan antusiasme yang tinggi.',
                'evaluation' => 'Kegiatan berjalan lancar. Peserta dapat memahami materi yang disampaikan.',
                'follow_up' => 'Monitoring implementasi hasil bimbingan dalam kehidupan sehari-hari.',
            ]);

            // Attach participants
            $participantCount = match ($serviceType) {
                CounselingServiceType::Group => rand(3, 8),
                CounselingServiceType::Classroom => rand(15, 25),
                CounselingServiceType::LargeClass => rand(30, 40),
                default => 5,
            };

            $participants = $students->random(min($participantCount, $students->count()));
            foreach ($participants as $participant) {
                $groupCounseling->participants()->attach($participant->id, [
                    'notes' => 'Peserta aktif dan kooperatif selama kegiatan.',
                ]);
            }
        }

        // Homeroom Consultation
        if ($teacher) {
            $this->command->info('Creating Homeroom Consultation records...');
            foreach ($students->take(3) as $student) {
                HomeroomConsultation::query()->create([
                    'academic_year_id' => $academicYear->id,
                    'counselor_id' => $counselor->id,
                    'teacher_id' => $teacher->id,
                    'student_id' => $student->id,
                    'consultation_date' => now()->addDays(rand(1, 30)),
                    'topic' => 'Konsultasi mengenai '.$this->getRandomConsultationTopic(),
                    'recommendation' => 'Wali kelas disarankan untuk '.$this->getRandomRecommendation(),
                    'evaluation' => 'Konsultasi berjalan produktif. Wali kelas memahami kondisi siswa.',
                    'follow_up' => 'Koordinasi berkelanjutan antara Guru BK dan Wali Kelas untuk monitoring siswa.',
                ]);
            }
        }

        // Parent Consultation
        if ($guardian) {
            $this->command->info('Creating Parent Consultation records...');
            foreach ($students->take(3) as $student) {
                ParentConsultation::query()->create([
                    'academic_year_id' => $academicYear->id,
                    'counselor_id' => $counselor->id,
                    'guardian_id' => $guardian->id,
                    'student_id' => $student->id,
                    'scheduled_at' => now()->addDays(rand(1, 30)),
                    'status' => ['scheduled', 'completed'][rand(0, 1)],
                    'requested_by' => ['counselor', 'parent'][rand(0, 1)],
                    'topic' => 'Pembahasan mengenai '.$this->getRandomParentTopic(),
                    'notes' => 'Orang tua menyampaikan kekhawatiran mengenai perkembangan anak.',
                    'result' => 'Tercapai kesepahaman antara orang tua dan guru BK mengenai penanganan siswa.',
                    'evaluation' => 'Konsultasi berjalan baik. Orang tua kooperatif dan terbuka.',
                    'follow_up' => 'Orang tua akan memantau perkembangan anak di rumah dan melaporkan ke Guru BK.',
                    'agreement' => 'Orang tua setuju untuk mendukung program bimbingan yang diberikan sekolah.',
                ]);
            }
        }

        // External Consultation
        $this->command->info('Creating External Consultation records...');
        foreach ($students->take(2) as $student) {
            ExternalConsultation::query()->create([
                'academic_year_id' => $academicYear->id,
                'counselor_id' => $counselor->id,
                'student_id' => $student->id,
                'consultation_date' => now()->addDays(rand(1, 30)),
                'external_party_name' => $this->getRandomExternalParty(),
                'external_party_role' => ['Psikolog', 'Dokter', 'Terapis', 'Konselor Profesional'][rand(0, 3)],
                'topic' => 'Rujukan untuk penanganan '.$this->getRandomExternalTopic(),
                'notes' => 'Siswa memerlukan penanganan lebih lanjut dari pihak profesional eksternal.',
                'evaluation' => 'Pihak eksternal memberikan rekomendasi penanganan yang komprehensif.',
                'follow_up' => 'Koordinasi berkelanjutan dengan pihak eksternal untuk monitoring perkembangan siswa.',
            ]);
        }

        $this->command->info('Demo counseling data created successfully!');
    }

    private function getRandomProblem(): string
    {
        $problems = [
            'memahami materi pelajaran matematika',
            'berinteraksi dengan teman sebaya',
            'mengelola emosi dan stres',
            'menentukan pilihan karir masa depan',
            'konsentrasi saat belajar',
            'motivasi belajar yang menurun',
        ];

        return $problems[array_rand($problems)];
    }

    private function getRandomApproach(): string
    {
        $approaches = [
            'person-centered counseling',
            'cognitive behavioral therapy',
            'solution-focused brief therapy',
            'rational emotive behavior therapy',
            'reality therapy',
        ];

        return $approaches[array_rand($approaches)];
    }

    private function getRandomTopic(CounselingServiceType $type): string
    {
        $topics = match ($type) {
            CounselingServiceType::Group => [
                'Membangun Kerjasama Tim',
                'Mengelola Konflik Antar Teman',
                'Meningkatkan Motivasi Belajar',
                'Pengembangan Keterampilan Sosial',
            ],
            CounselingServiceType::Classroom => [
                'Orientasi Tahun Ajaran Baru',
                'Persiapan Ujian Nasional',
                'Pemilihan Jurusan dan Karir',
                'Manajemen Waktu dan Belajar Efektif',
            ],
            CounselingServiceType::LargeClass => [
                'Sosialisasi Tata Tertib Sekolah',
                'Pencegahan Bullying dan Kekerasan',
                'Pendidikan Karakter dan Nilai',
                'Kesehatan Mental Remaja',
            ],
            default => ['Topik Umum'],
        };

        return $topics[array_rand($topics)];
    }

    private function getRandomGoal(): string
    {
        $goals = [
            'kemampuan komunikasi siswa',
            'kepercayaan diri dan self-esteem',
            'keterampilan problem solving',
            'kesadaran akan potensi diri',
            'kemampuan beradaptasi',
        ];

        return $goals[array_rand($goals)];
    }

    private function getRandomConsultationTopic(): string
    {
        $topics = [
            'perkembangan akademik siswa',
            'perilaku siswa di kelas',
            'interaksi sosial siswa',
            'kehadiran dan kedisiplinan siswa',
            'potensi dan bakat siswa',
        ];

        return $topics[array_rand($topics)];
    }

    private function getRandomRecommendation(): string
    {
        $recommendations = [
            'memberikan perhatian khusus kepada siswa',
            'melakukan pendekatan personal',
            'mengkomunikasikan dengan orang tua',
            'memberikan motivasi dan dukungan',
            'memantau perkembangan siswa secara berkala',
        ];

        return $recommendations[array_rand($recommendations)];
    }

    private function getRandomParentTopic(): string
    {
        $topics = [
            'prestasi akademik anak',
            'perilaku dan sikap anak',
            'pergaulan dan pertemanan anak',
            'minat dan bakat anak',
            'kesulitan belajar anak',
        ];

        return $topics[array_rand($topics)];
    }

    private function getRandomExternalParty(): string
    {
        $parties = [
            'Dr. Ahmad Wijaya, M.Psi',
            'Klinik Psikologi Harapan',
            'RS. Jiwa Prof. Dr. Soerojo',
            'Lembaga Konseling Keluarga',
            'Pusat Terapi Anak dan Remaja',
        ];

        return $parties[array_rand($parties)];
    }

    private function getRandomExternalTopic(): string
    {
        $topics = [
            'gangguan kecemasan',
            'kesulitan belajar spesifik',
            'masalah perilaku',
            'trauma psikologis',
            'gangguan perkembangan',
        ];

        return $topics[array_rand($topics)];
    }
}
