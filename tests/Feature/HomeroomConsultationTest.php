<?php

namespace Tests\Feature;

use App\Models\AcademicYear;
use App\Models\HomeroomConsultation;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HomeroomConsultationTest extends TestCase
{
    use RefreshDatabase;

    public function test_guru_bk_can_view_homeroom_consultations_index(): void
    {
        $guruBk = User::factory()->guruBk()->create();
        HomeroomConsultation::factory()->count(3)->create(['counselor_id' => $guruBk->id]);

        $response = $this->actingAs($guruBk)->get(route('guru-bk.homeroom-consultations.index'));

        $response->assertStatus(200);
        $response->assertViewIs('homeroom-consultations.index');
    }

    public function test_guru_bk_can_filter_by_academic_year(): void
    {
        $guruBk = User::factory()->guruBk()->create();
        $year = AcademicYear::factory()->create();
        HomeroomConsultation::factory()->create(['counselor_id' => $guruBk->id, 'academic_year_id' => $year->id]);

        $response = $this->actingAs($guruBk)->get(route('guru-bk.homeroom-consultations.index', ['academic_year_id' => $year->id]));

        $response->assertStatus(200);
    }

    public function test_guru_bk_can_view_create_form(): void
    {
        $guruBk = User::factory()->guruBk()->create();

        $response = $this->actingAs($guruBk)->get(route('guru-bk.homeroom-consultations.create'));

        $response->assertStatus(200);
        $response->assertViewIs('homeroom-consultations.create');
    }

    public function test_guru_bk_can_create_homeroom_consultation(): void
    {
        $guruBk = User::factory()->guruBk()->create();
        $academicYear = AcademicYear::factory()->create();
        $teacher = Teacher::factory()->create();
        $student = Student::factory()->create();

        $response = $this->actingAs($guruBk)->post(route('guru-bk.homeroom-consultations.store'), [
            'academic_year_id' => $academicYear->id,
            'teacher_id' => $teacher->id,
            'student_id' => $student->id,
            'consultation_date' => '2026-04-10 10:00',
            'topic' => 'Siswa sering tidak mengumpulkan tugas tepat waktu.',
            'recommendation' => 'Berikan perhatian ekstra kepada siswa bersangkutan.',
            'follow_up' => 'Pantau siswa selama 2 minggu ke depan.',
        ]);

        $response->assertRedirect(route('guru-bk.homeroom-consultations.index'));
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('homeroom_consultations', [
            'counselor_id' => $guruBk->id,
            'teacher_id' => $teacher->id,
            'student_id' => $student->id,
        ]);
    }

    public function test_store_validates_required_fields(): void
    {
        $guruBk = User::factory()->guruBk()->create();

        $response = $this->actingAs($guruBk)->post(route('guru-bk.homeroom-consultations.store'), []);

        $response->assertSessionHasErrors(['academic_year_id', 'teacher_id', 'student_id', 'consultation_date', 'topic']);
    }

    public function test_store_validates_teacher_exists(): void
    {
        $guruBk = User::factory()->guruBk()->create();
        $academicYear = AcademicYear::factory()->create();
        $student = Student::factory()->create();

        $response = $this->actingAs($guruBk)->post(route('guru-bk.homeroom-consultations.store'), [
            'academic_year_id' => $academicYear->id,
            'teacher_id' => 99999,
            'student_id' => $student->id,
            'consultation_date' => '2026-04-10 10:00',
            'topic' => 'Topik konsultasi.',
        ]);

        $response->assertSessionHasErrors(['teacher_id']);
    }

    public function test_guru_bk_can_view_consultation_detail(): void
    {
        $guruBk = User::factory()->guruBk()->create();
        $consultation = HomeroomConsultation::factory()->create(['counselor_id' => $guruBk->id]);

        $response = $this->actingAs($guruBk)->get(route('guru-bk.homeroom-consultations.show', $consultation));

        $response->assertStatus(200);
        $response->assertViewIs('homeroom-consultations.show');
    }

    public function test_guru_bk_can_view_edit_form(): void
    {
        $guruBk = User::factory()->guruBk()->create();
        $consultation = HomeroomConsultation::factory()->create(['counselor_id' => $guruBk->id]);

        $response = $this->actingAs($guruBk)->get(route('guru-bk.homeroom-consultations.edit', $consultation));

        $response->assertStatus(200);
        $response->assertViewIs('homeroom-consultations.edit');
    }

    public function test_guru_bk_can_update_homeroom_consultation(): void
    {
        $guruBk = User::factory()->guruBk()->create();
        $academicYear = AcademicYear::factory()->create();
        $teacher = Teacher::factory()->create();
        $student = Student::factory()->create();
        $consultation = HomeroomConsultation::factory()->create([
            'counselor_id' => $guruBk->id,
            'academic_year_id' => $academicYear->id,
            'teacher_id' => $teacher->id,
            'student_id' => $student->id,
        ]);

        $response = $this->actingAs($guruBk)->put(route('guru-bk.homeroom-consultations.update', $consultation), [
            'academic_year_id' => $academicYear->id,
            'teacher_id' => $teacher->id,
            'student_id' => $student->id,
            'consultation_date' => '2026-04-15 09:00',
            'topic' => 'Topik yang diperbarui.',
            'recommendation' => 'Rekomendasi baru.',
            'follow_up' => null,
        ]);

        $response->assertRedirect(route('guru-bk.homeroom-consultations.show', $consultation));
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('homeroom_consultations', [
            'id' => $consultation->id,
            'topic' => 'Topik yang diperbarui.',
        ]);
    }

    public function test_guru_bk_can_delete_homeroom_consultation(): void
    {
        $guruBk = User::factory()->guruBk()->create();
        $consultation = HomeroomConsultation::factory()->create(['counselor_id' => $guruBk->id]);

        $response = $this->actingAs($guruBk)->delete(route('guru-bk.homeroom-consultations.destroy', $consultation));

        $response->assertRedirect(route('guru-bk.homeroom-consultations.index'));
        $response->assertSessionHas('success');
        $this->assertDatabaseMissing('homeroom_consultations', ['id' => $consultation->id]);
    }

    public function test_non_guru_bk_cannot_access_homeroom_consultations(): void
    {
        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin)->get(route('guru-bk.homeroom-consultations.index'));

        $response->assertStatus(403);
    }

    public function test_guest_cannot_access_homeroom_consultations(): void
    {
        $response = $this->get(route('guru-bk.homeroom-consultations.index'));

        $response->assertRedirect(route('login'));
    }
}
