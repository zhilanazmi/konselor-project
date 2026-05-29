<?php

namespace Database\Factories;

use App\Models\AcademicYear;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SubjectTeacherConsultation>
 */
class SubjectTeacherConsultationFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'academic_year_id' => AcademicYear::factory(),
            'counselor_id' => User::factory()->guruBk(),
            'teacher_id' => Teacher::factory(),
            'student_id' => Student::factory(),
            'subject_name' => fake()->randomElement(['Matematika', 'Bahasa Inggris', 'IPA', 'IPS']),
            'consultation_date' => fake()->dateTimeBetween('-1 month', 'now'),
            'topic' => fake()->paragraph(2),
            'recommendation' => fake()->paragraph(),
            'follow_up' => fake()->sentence(),
        ];
    }
}
