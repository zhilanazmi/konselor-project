<?php

namespace Tests\Feature;

use App\Models\AcademicYear;
use App\Models\Guardian;
use App\Models\ParentConsultation;
use App\Models\Student;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ParentConsultationTest extends TestCase
{
    use RefreshDatabase;

    public function test_guru_bk_can_view_parent_consultations_index(): void
    {
        $guruBk = User::factory()->guruBk()->create();
        ParentConsultation::factory()->count(3)->create(['counselor_id' => $guruBk->id]);

        $response = $this->actingAs($guruBk)->get(route('guru-bk.parent-consultations.index'));

        $response->assertStatus(200);
        $response->assertViewIs('parent-consultations.index');
    }

    public function test_guru_bk_can_filter_index(): void
    {
        $guruBk = User::factory()->guruBk()->create();

        $year = AcademicYear::factory()->create();
        ParentConsultation::factory()->create([
            'counselor_id' => $guruBk->id,
            'academic_year_id' => $year->id,
            'status' => 'completed',
            'requested_by' => 'orang_tua',
        ]);

        $response = $this->actingAs($guruBk)->get(route('guru-bk.parent-consultations.index', [
            'academic_year_id' => $year->id,
            'status' => 'completed',
            'requested_by' => 'orang_tua',
        ]));

        $response->assertStatus(200);
        $response->assertViewHas('consultations');
    }

    public function test_guru_bk_can_view_create_form(): void
    {
        $guruBk = User::factory()->guruBk()->create();

        $response = $this->actingAs($guruBk)->get(route('guru-bk.parent-consultations.create'));

        $response->assertStatus(200);
        $response->assertViewIs('parent-consultations.create');
    }

    public function test_guru_bk_can_create_parent_consultation(): void
    {
        $guruBk = User::factory()->guruBk()->create();
        $academicYear = AcademicYear::factory()->create();
        $guardian = Guardian::factory()->create();
        $student = Student::factory()->create();

        $response = $this->actingAs($guruBk)->post(route('guru-bk.parent-consultations.store'), [
            'academic_year_id' => $academicYear->id,
            'guardian_id' => $guardian->id,
            'student_id' => $student->id,
            'scheduled_at' => '2026-05-10 10:00',
            'status' => 'scheduled',
            'requested_by' => 'guru_bk',
            'topic' => 'Kehadiran siswa semakin menurun.',
        ]);

        $response->assertRedirect(route('guru-bk.parent-consultations.index'));
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('parent_consultations', [
            'counselor_id' => $guruBk->id,
            'guardian_id' => $guardian->id,
            'student_id' => $student->id,
            'status' => 'scheduled',
        ]);
    }

    public function test_store_validates_required_fields(): void
    {
        $guruBk = User::factory()->guruBk()->create();

        $response = $this->actingAs($guruBk)->post(route('guru-bk.parent-consultations.store'), []);

        $response->assertSessionHasErrors([
            'academic_year_id', 'guardian_id', 'student_id', 'scheduled_at', 'status', 'requested_by', 'topic',
        ]);
    }

    public function test_guru_bk_can_view_consultation_detail(): void
    {
        $guruBk = User::factory()->guruBk()->create();
        $consultation = ParentConsultation::factory()->create(['counselor_id' => $guruBk->id]);

        $response = $this->actingAs($guruBk)->get(route('guru-bk.parent-consultations.show', $consultation));

        $response->assertStatus(200);
        $response->assertViewIs('parent-consultations.show');
    }

    public function test_guru_bk_can_view_edit_form(): void
    {
        $guruBk = User::factory()->guruBk()->create();
        $consultation = ParentConsultation::factory()->create(['counselor_id' => $guruBk->id]);

        $response = $this->actingAs($guruBk)->get(route('guru-bk.parent-consultations.edit', $consultation));

        $response->assertStatus(200);
        $response->assertViewIs('parent-consultations.edit');
    }

    public function test_guru_bk_can_update_parent_consultation(): void
    {
        $guruBk = User::factory()->guruBk()->create();
        $academicYear = AcademicYear::factory()->create();
        $guardian = Guardian::factory()->create();
        $student = Student::factory()->create();
        $consultation = ParentConsultation::factory()->create([
            'counselor_id' => $guruBk->id,
            'academic_year_id' => $academicYear->id,
            'guardian_id' => $guardian->id,
            'student_id' => $student->id,
            'status' => 'requested',
            'topic' => 'Diskusi awal',
        ]);

        $response = $this->actingAs($guruBk)->put(route('guru-bk.parent-consultations.update', $consultation), [
            'academic_year_id' => $academicYear->id,
            'guardian_id' => $guardian->id,
            'student_id' => $student->id,
            'scheduled_at' => '2026-05-15 09:00',
            'status' => 'completed',
            'requested_by' => 'orang_tua',
            'topic' => 'Diskusi setelah direvisi',
            'result' => 'Berjalan lancar.',
            'agreement' => 'Akan dipantau kembali.',
        ]);

        $response->assertRedirect(route('guru-bk.parent-consultations.show', $consultation));
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('parent_consultations', [
            'id' => $consultation->id,
            'status' => 'completed',
            'topic' => 'Diskusi setelah direvisi',
            'result' => 'Berjalan lancar.',
        ]);
    }

    public function test_guru_bk_can_delete_parent_consultation(): void
    {
        $guruBk = User::factory()->guruBk()->create();
        $consultation = ParentConsultation::factory()->create(['counselor_id' => $guruBk->id]);

        $response = $this->actingAs($guruBk)->delete(route('guru-bk.parent-consultations.destroy', $consultation));

        $response->assertRedirect(route('guru-bk.parent-consultations.index'));
        $response->assertSessionHas('success');
        $this->assertDatabaseMissing('parent_consultations', ['id' => $consultation->id]);
    }

    public function test_non_guru_bk_cannot_access_parent_consultations(): void
    {
        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin)->get(route('guru-bk.parent-consultations.index'));

        $response->assertStatus(403);
    }
}
