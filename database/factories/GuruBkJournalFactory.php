<?php

namespace Database\Factories;

use App\Models\AcademicYear;
use App\Models\GuruBkJournal;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<GuruBkJournal>
 */
class GuruBkJournalFactory extends Factory
{
    protected $model = GuruBkJournal::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'academic_year_id' => AcademicYear::factory(),
            'counselor_id' => User::factory()->guruBk(),
            'date' => fake()->date(),
            'activity_type' => fake()->randomElement(['layanan_dasar', 'layanan_responsif', 'layanan_perencanaan', 'dukungan_sistem']),
            'title' => fake()->sentence(5),
            'description' => fake()->paragraph(),
            'target_group' => 'Siswa Kelas '.fake()->randomElement(['X', 'XI', 'XII']),
            'location' => fake()->randomElement(['Ruang BK', 'Kelas', 'Aula']),
            'duration_minutes' => fake()->numberBetween(30, 120),
            'notes' => fake()->optional()->paragraph(),
        ];
    }
}
