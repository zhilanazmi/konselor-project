<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Teacher>
 */
class TeacherFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory()->guru(),
            'nip' => fake()->unique()->numerify('##################'),
            'full_name' => fake()->name(),
            'subject' => fake()->randomElement([
                'Matematika', 'Bahasa Indonesia', 'Bahasa Inggris',
                'IPA', 'IPS', 'PKn', 'Seni Budaya', 'PJOK',
                'Prakarya', 'Agama', 'Informatika',
            ]),
        ];
    }
}
