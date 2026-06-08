<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\CounselingRequest;
use App\Models\Student;
use App\Models\User;
use Illuminate\Database\Seeder;

class CounselingRequestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $counselorUser = User::where('role', UserRole::GuruBk)->first();
        $students = Student::all();

        if ($counselorUser && $students->isNotEmpty()) {
            $randomStudents = $students->random(min(5, $students->count()));
            foreach ($randomStudents as $student) {
                CounselingRequest::factory()->create([
                    'student_id' => $student->id,
                    'counselor_id' => $counselorUser->id,
                ]);
            }
        }
    }
}
