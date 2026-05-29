<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Admin User
        User::query()->updateOrCreate(
            ['email' => 'admin@konselorkita.com'],
            [
                'name' => 'Administrator',
                'email' => 'admin@konselorkita.com',
                'password' => Hash::make('password'),
                'role' => UserRole::Admin,
            ]
        );

        // Create Guru BK User
        User::query()->updateOrCreate(
            ['email' => 'gurubk@konselorkita.com'],
            [
                'name' => 'Ibu Sari (Guru BK)',
                'email' => 'gurubk@konselorkita.com',
                'password' => Hash::make('password'),
                'role' => UserRole::GuruBk,
            ]
        );

        // Create Wali Kelas User
        User::query()->updateOrCreate(
            ['email' => 'walikelas@konselorkita.com'],
            [
                'name' => 'Pak Budi (Wali Kelas)',
                'email' => 'walikelas@konselorkita.com',
                'password' => Hash::make('password'),
                'role' => UserRole::WaliKelas,
            ]
        );

        $this->command->info('Users created successfully!');
        $this->command->info('Admin: admin@konselorkita.com / password');
        $this->command->info('Guru BK: gurubk@konselorkita.com / password');
        $this->command->info('Wali Kelas: walikelas@konselorkita.com / password');
    }
}
