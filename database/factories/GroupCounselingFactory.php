<?php

namespace Database\Factories;

use App\Models\AcademicYear;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\GroupCounseling>
 */
class GroupCounselingFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'academic_year_id' => AcademicYear::factory(),
            'counselor_id' => User::factory()->guruBk(),
            'topic' => fake()->sentence(4),
            'description' => fake()->paragraph(),
            'method' => fake()->sentence(6),
            'scheduled_at' => fake()->dateTimeBetween('now', '+1 month'),
            'status' => fake()->randomElement(['scheduled', 'ongoing', 'completed']),
            'result' => null,
            'evaluation' => null,
        ];
    }
}
