<?php

namespace Tests\Feature;

use App\Models\AcademicYear;
use App\Models\Student;
use App\Models\SubjectTeacherConsultation;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SubjectTeacherConsultationTest extends TestCase
{
    use RefreshDatabase;

    public function test_guru_bk_can_view_subject_teacher_consultations_index(): void
    {
        $guruBk = User::factory()->guruBk()->create();
        SubjectTeacherConsultation::factory()->count(3)->create(['counselor_id' => $guruBk->id]);

        $response = $this->actingAs($guruBk)->get(route('guru-bk.subject-teacher-consultations.index'));

        $response->assertStatus(200);
        $response->assertViewIs('subject-teacher-consultations.index');
    }

    public function test_guru_bk_can_filter_by_academic_year(): void
    {
        $guruBk = User::factory()->guruBk()->create();
        $year = AcademicYear::factory()->create();
        SubjectTeacherConsultation::factory()->create(['counselor_id' => $guruBk->id, 'academic_year_id' => $year->id]);

        $response = $this->actingAs($guruBk)->get(route('guru-bk.subject-teacher-consultations.index', ['academic_year_id' => $year->id]));

        $response->assertStatus(200);
    }

    public function test_guru_bk_can_view_create_form(): void
    {
        $guruBk = User::factory()->guruBk()->create();

        $response = $this->actingAs($guruBk)->get(route('guru-bk.subject-teacher-consultations.create'));

        $response->assertStatus(200);
        $response->assertViewIs('subject-teacher-consultations.create');
    }

    public function test_guru_bk_can_create_subject_teacher_consultation(): void
    {
        $guruBk = User::factory()->guruBk()->create();
        $academicYear = AcademicYear::factory()->create();
        $teacher = Teacher::factory()->create();
        $student = Student::factory()->create();

        $response = $this->actingAs($guruBk)->post(route('guru-bk.subject-teacher-consultations.store'), [
            'academic_year_id' => $academicYear->id,
            'teacher_id' => $teacher->id,
            'student_id' => $student->id,
            'subject_name' => 'Matematika Peminatan',
            'consultation_date' => '2026-04-10 10:00',
            'topic' => 'Siswa kesulitan memahami materi kalkulus.',
            'recommendation' => 'Berikan kelas tambahan.',
            'follow_up' => 'Pantau nilai kuis berikutnya.',
        ]);

        $response->assertRedirect(route('guru-bk.subject-teacher-consultations.index'));
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('subject_teacher_consultations', [
            'counselor_id' => $guruBk->id,
            'teacher_id' => $teacher->id,
            'student_id' => $student->id,
            'subject_name' => 'Matematika Peminatan',
        ]);
    }

    public function test_store_validates_required_fields(): void
    {
        $guruBk = User::factory()->guruBk()->create();

        $response = $this->actingAs($guruBk)->post(route('guru-bk.subject-teacher-consultations.store'), []);

        $response->assertSessionHasErrors(['academic_year_id', 'teacher_id', 'student_id', 'subject_name', 'consultation_date', 'topic']);
    }

    public function test_guru_bk_can_view_consultation_detail(): void
    {
        $guruBk = User::factory()->guruBk()->create();
        $consultation = SubjectTeacherConsultation::factory()->create(['counselor_id' => $guruBk->id]);

        $response = $this->actingAs($guruBk)->get(route('guru-bk.subject-teacher-consultations.show', $consultation));

        $response->assertStatus(200);
        $response->assertViewIs('subject-teacher-consultations.show');
    }

    public function test_guru_bk_can_view_edit_form(): void
    {
        $guruBk = User::factory()->guruBk()->create();
        $consultation = SubjectTeacherConsultation::factory()->create(['counselor_id' => $guruBk->id]);

        $response = $this->actingAs($guruBk)->get(route('guru-bk.subject-teacher-consultations.edit', $consultation));

        $response->assertStatus(200);
        $response->assertViewIs('subject-teacher-consultations.edit');
    }

    public function test_guru_bk_can_update_subject_teacher_consultation(): void
    {
        $guruBk = User::factory()->guruBk()->create();
        $academicYear = AcademicYear::factory()->create();
        $teacher = Teacher::factory()->create();
        $student = Student::factory()->create();
        $consultation = SubjectTeacherConsultation::factory()->create([
            'counselor_id' => $guruBk->id,
            'academic_year_id' => $academicYear->id,
            'teacher_id' => $teacher->id,
            'student_id' => $student->id,
            'subject_name' => 'Biologi Lama',
        ]);

        $response = $this->actingAs($guruBk)->put(route('guru-bk.subject-teacher-consultations.update', $consultation), [
            'academic_year_id' => $academicYear->id,
            'teacher_id' => $teacher->id,
            'student_id' => $student->id,
            'subject_name' => 'Biologi Baru',
            'consultation_date' => '2026-04-15 09:00',
            'topic' => 'Topik yang diperbarui.',
            'recommendation' => 'Rekomendasi baru.',
            'follow_up' => null,
        ]);

        $response->assertRedirect(route('guru-bk.subject-teacher-consultations.show', $consultation));
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('subject_teacher_consultations', [
            'id' => $consultation->id,
            'subject_name' => 'Biologi Baru',
            'topic' => 'Topik yang diperbarui.',
        ]);
    }

    public function test_guru_bk_can_delete_subject_teacher_consultation(): void
    {
        $guruBk = User::factory()->guruBk()->create();
        $consultation = SubjectTeacherConsultation::factory()->create(['counselor_id' => $guruBk->id]);

        $response = $this->actingAs($guruBk)->delete(route('guru-bk.subject-teacher-consultations.destroy', $consultation));

        $response->assertRedirect(route('guru-bk.subject-teacher-consultations.index'));
        $response->assertSessionHas('success');
        $this->assertDatabaseMissing('subject_teacher_consultations', ['id' => $consultation->id]);
    }

    public function test_non_guru_bk_cannot_access_subject_teacher_consultations(): void
    {
        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin)->get(route('guru-bk.subject-teacher-consultations.index'));

        $response->assertStatus(403);
    }

    public function test_guest_cannot_access_subject_teacher_consultations(): void
    {
        $response = $this->get(route('guru-bk.subject-teacher-consultations.index'));

        $response->assertRedirect(route('login'));
    }
}
