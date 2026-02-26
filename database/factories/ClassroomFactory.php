<?php

namespace Database\Factories;

use App\Models\AcademicYear;
use App\Models\Teacher;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Classroom>
 */
class ClassroomFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $grade = fake()->randomElement(['7', '8', '9']);
        $letter = fake()->randomElement(['A', 'B', 'C', 'D']);
        $gradeLabel = match ($grade) {
            '7' => 'VII',
            '8' => 'VIII',
            '9' => 'IX',
        };

        return [
            'academic_year_id' => AcademicYear::factory(),
            'homeroom_teacher_id' => Teacher::factory(),
            'name' => "{$gradeLabel}-{$letter}",
            'grade' => $grade,
        ];
    }
}
