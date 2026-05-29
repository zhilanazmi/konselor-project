<?php

namespace Database\Seeders;

use App\Models\PopularTopic;
use App\Models\User;
use Illuminate\Database\Seeder;

class PopularTopicSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $gurubk = User::query()->where('role', 'guru_bk')->first();

        if (! $gurubk) {
            $this->command->warn('Tidak ada user dengan role guru_bk. Silakan buat user guru_bk terlebih dahulu.');

            return;
        }

        $topics = [
            [
                'title' => 'Masalah Belajar',
                'description' => 'Susah fokus, nilai turun, atau bingung pilih jurusan? Kami siap membantu menemukan cara belajar yang tepat untukmu.',
                'icon' => 'solar:book-bold',
                'icon_color' => '#3B82F6',
                'order' => 1,
                'is_active' => true,
                'created_by' => $gurubk->id,
            ],
            [
                'title' => 'Bullying & Teman',
                'description' => 'Dikucilkan, diejek, atau punya masalah dengan teman? Ceritakan pada kami, kamu tidak sendirian.',
                'icon' => 'solar:users-group-rounded-bold',
                'icon_color' => '#EF4444',
                'order' => 2,
                'is_active' => true,
                'created_by' => $gurubk->id,
            ],
            [
                'title' => 'Masalah Keluarga',
                'description' => 'Masalah di rumah yang bikin kamu nggak nyaman? Kami siap mendengarkan dan membantu mencari solusi.',
                'icon' => 'solar:home-bold',
                'icon_color' => '#F97316',
                'order' => 3,
                'is_active' => true,
                'created_by' => $gurubk->id,
            ],
            [
                'title' => 'Masa Depan & Karir',
                'description' => 'Bingung mau lanjut SMA atau SMK mana? Atau masih bingung cita-cita? Yuk diskusi bareng!',
                'icon' => 'solar:rocket-bold',
                'icon_color' => '#10B981',
                'order' => 4,
                'is_active' => true,
                'created_by' => $gurubk->id,
            ],
            [
                'title' => 'Masalah Pribadi',
                'description' => 'Merasa cemas, sedih, atau ada masalah pribadi lainnya? Kami siap mendengarkan tanpa menghakimi.',
                'icon' => 'solar:heart-bold',
                'icon_color' => '#EC4899',
                'order' => 5,
                'is_active' => true,
                'created_by' => $gurubk->id,
            ],
            [
                'title' => 'Motivasi & Semangat',
                'description' => 'Merasa down atau kehilangan motivasi? Mari kita bangkitkan semangat belajarmu lagi!',
                'icon' => 'solar:fire-bold',
                'icon_color' => '#F59E0B',
                'order' => 6,
                'is_active' => true,
                'created_by' => $gurubk->id,
            ],
            [
                'title' => 'Pergaulan & Sosial',
                'description' => 'Susah bergaul, minder, atau ada masalah dalam berinteraksi dengan orang lain? Kami bisa bantu.',
                'icon' => 'solar:chat-round-dots-bold',
                'icon_color' => '#8B5CF6',
                'order' => 7,
                'is_active' => true,
                'created_by' => $gurubk->id,
            ],
            [
                'title' => 'Stress & Tekanan',
                'description' => 'Merasa tertekan dengan tugas, ujian, atau ekspektasi orang lain? Yuk cerita dan cari solusinya.',
                'icon' => 'solar:shield-check-bold',
                'icon_color' => '#06B6D4',
                'order' => 8,
                'is_active' => true,
                'created_by' => $gurubk->id,
            ],
        ];

        foreach ($topics as $topic) {
            PopularTopic::query()->create($topic);
        }

        $this->command->info('Popular topics seeded successfully!');
    }
}
