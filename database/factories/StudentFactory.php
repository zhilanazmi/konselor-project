<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Student>
 */
class StudentFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory()->siswa(),
            'nis' => fake()->unique()->numerify('########'),
            'full_name' => fake()->name(),
            'gender' => fake()->randomElement(['L', 'P']),
            'birth_date' => fake()->dateTimeBetween('-15 years', '-12 years'),
            'birth_place' => fake()->city(),
            'address' => fake()->address(),
            'phone' => fake()->phoneNumber(),
        ];
    }
}
