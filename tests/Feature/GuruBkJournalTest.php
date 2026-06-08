<?php

namespace Tests\Feature;

use App\Models\AcademicYear;
use App\Models\GuruBkJournal;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GuruBkJournalTest extends TestCase
{
    use RefreshDatabase;

    public function test_guru_bk_can_view_journals_index(): void
    {
        $guruBk = User::factory()->guruBk()->create();
        GuruBkJournal::factory()->count(3)->create(['counselor_id' => $guruBk->id]);

        $response = $this->actingAs($guruBk)->get(route('guru-bk.guru-bk-journals.index'));

        $response->assertStatus(200);
        $response->assertViewIs('guru-bk-journals.index');
        $response->assertViewHas('journals');
    }

    public function test_guru_bk_can_filter_journals_by_academic_year_and_type(): void
    {
        $guruBk = User::factory()->guruBk()->create();
        $academicYear1 = AcademicYear::factory()->create();
        $academicYear2 = AcademicYear::factory()->create();

        GuruBkJournal::factory()->create([
            'counselor_id' => $guruBk->id,
            'academic_year_id' => $academicYear1->id,
            'activity_type' => 'layanan_dasar',
        ]);
        GuruBkJournal::factory()->create([
            'counselor_id' => $guruBk->id,
            'academic_year_id' => $academicYear2->id,
            'activity_type' => 'layanan_responsif',
        ]);

        // Filter by academic year
        $response = $this->actingAs($guruBk)->get(route('guru-bk.guru-bk-journals.index', [
            'academic_year_id' => $academicYear1->id,
        ]));
        $response->assertStatus(200);

        // Filter by activity type
        $response = $this->actingAs($guruBk)->get(route('guru-bk.guru-bk-journals.index', [
            'activity_type' => 'layanan_responsif',
        ]));
        $response->assertStatus(200);
    }

    public function test_guru_bk_can_view_create_journal_form(): void
    {
        $guruBk = User::factory()->guruBk()->create();

        $response = $this->actingAs($guruBk)->get(route('guru-bk.guru-bk-journals.create'));

        $response->assertStatus(200);
        $response->assertViewIs('guru-bk-journals.create');
    }

    public function test_guru_bk_can_store_journal(): void
    {
        $guruBk = User::factory()->guruBk()->create();
        $academicYear = AcademicYear::factory()->create();

        $response = $this->actingAs($guruBk)->post(route('guru-bk.guru-bk-journals.store'), [
            'academic_year_id' => $academicYear->id,
            'date' => '2026-06-08',
            'activity_type' => 'layanan_dasar',
            'title' => 'Bimbingan Klasikal Kelas X',
            'description' => 'Membahas tentang minat dan bakat.',
            'target_group' => 'Kelas X-A',
            'location' => 'Kelas X-A',
            'duration_minutes' => 45,
            'notes' => 'Siswa sangat antusias.',
        ]);

        $response->assertRedirect(route('guru-bk.guru-bk-journals.index'));
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('guru_bk_journals', [
            'counselor_id' => $guruBk->id,
            'title' => 'Bimbingan Klasikal Kelas X',
            'activity_type' => 'layanan_dasar',
        ]);
    }

    public function test_store_validates_required_fields(): void
    {
        $guruBk = User::factory()->guruBk()->create();

        $response = $this->actingAs($guruBk)->post(route('guru-bk.guru-bk-journals.store'), []);

        $response->assertSessionHasErrors(['academic_year_id', 'date', 'activity_type', 'title']);
    }

    public function test_guru_bk_can_view_journal_details(): void
    {
        $guruBk = User::factory()->guruBk()->create();
        $journal = GuruBkJournal::factory()->create(['counselor_id' => $guruBk->id]);

        $response = $this->actingAs($guruBk)->get(route('guru-bk.guru-bk-journals.show', $journal));

        $response->assertStatus(200);
        $response->assertViewIs('guru-bk-journals.show');
        $response->assertViewHas('journal');
    }

    public function test_guru_bk_can_view_edit_journal_form(): void
    {
        $guruBk = User::factory()->guruBk()->create();
        $journal = GuruBkJournal::factory()->create(['counselor_id' => $guruBk->id]);

        $response = $this->actingAs($guruBk)->get(route('guru-bk.guru-bk-journals.edit', $journal));

        $response->assertStatus(200);
        $response->assertViewIs('guru-bk-journals.edit');
        $response->assertViewHas('journal');
    }

    public function test_guru_bk_can_update_journal(): void
    {
        $guruBk = User::factory()->guruBk()->create();
        $academicYear = AcademicYear::factory()->create();
        $journal = GuruBkJournal::factory()->create([
            'counselor_id' => $guruBk->id,
            'academic_year_id' => $academicYear->id,
        ]);

        $response = $this->actingAs($guruBk)->put(route('guru-bk.guru-bk-journals.update', $journal), [
            'academic_year_id' => $academicYear->id,
            'date' => '2026-06-09',
            'activity_type' => 'layanan_responsif',
            'title' => 'Konseling Individu Ahmad',
            'description' => 'Membantu mengatasi masalah kehadiran.',
            'target_group' => 'Ahmad (Kelas X)',
            'location' => 'Ruang BK',
            'duration_minutes' => 60,
            'notes' => 'Masalah selesai.',
        ]);

        $response->assertRedirect(route('guru-bk.guru-bk-journals.show', $journal));
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('guru_bk_journals', [
            'id' => $journal->id,
            'title' => 'Konseling Individu Ahmad',
            'activity_type' => 'layanan_responsif',
        ]);
    }

    public function test_guru_bk_can_delete_journal(): void
    {
        $guruBk = User::factory()->guruBk()->create();
        $journal = GuruBkJournal::factory()->create(['counselor_id' => $guruBk->id]);

        $response = $this->actingAs($guruBk)->delete(route('guru-bk.guru-bk-journals.destroy', $journal));

        $response->assertRedirect(route('guru-bk.guru-bk-journals.index'));
        $response->assertSessionHas('success');
        $this->assertDatabaseMissing('guru_bk_journals', ['id' => $journal->id]);
    }

    public function test_non_guru_bk_cannot_access_journals(): void
    {
        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin)->get(route('guru-bk.guru-bk-journals.index'));

        $response->assertStatus(403);
    }

    public function test_guest_cannot_access_journals(): void
    {
        $response = $this->get(route('guru-bk.guru-bk-journals.index'));

        $response->assertRedirect(route('login'));
    }
}
