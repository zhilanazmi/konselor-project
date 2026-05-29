<?php

namespace Database\Factories;

use App\Models\AcademicYear;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\HomeroomConsultation>
 */
class HomeroomConsultationFactory extends Factory
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
            'consultation_date' => fake()->dateTimeBetween('-1 month', 'now'),
            'topic' => fake()->paragraph(2),
            'recommendation' => fake()->paragraph(),
            'follow_up' => fake()->sentence(),
        ];
    }
}
