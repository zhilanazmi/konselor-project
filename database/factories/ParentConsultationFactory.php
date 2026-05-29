<?php

namespace Database\Factories;

use App\Models\AcademicYear;
use App\Models\Guardian;
use App\Models\Student;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ParentConsultation>
 */
class ParentConsultationFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'academic_year_id' => AcademicYear::factory(),
            'counselor_id' => User::factory()->guruBk(),
            'guardian_id' => Guardian::factory(),
            'student_id' => Student::factory(),
            'scheduled_at' => fake()->dateTimeBetween('-1 month', '+1 month'),
            'status' => fake()->randomElement(['requested', 'scheduled', 'completed']),
            'requested_by' => fake()->randomElement(['guru_bk', 'orang_tua']),
            'topic' => fake()->paragraph(),
            'result' => fake()->optional()->paragraph(),
            'agreement' => fake()->optional()->paragraph(),
        ];
    }
}
