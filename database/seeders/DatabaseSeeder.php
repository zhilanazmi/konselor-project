<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\AcademicYear;
use App\Models\Classroom;
use App\Models\Guardian;
use App\Models\Student;
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
    }
}
