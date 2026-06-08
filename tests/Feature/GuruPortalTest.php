<?php

namespace Tests\Feature;

use App\Enums\UserRole;
use App\Models\AcademicYear;
use App\Models\Classroom;
use App\Models\HomeroomConsultation;
use App\Models\Student;
use App\Models\SubjectTeacherConsultation;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GuruPortalTest extends TestCase
{
    use RefreshDatabase;

    private User $guruUser;

    private Teacher $teacher;

    private AcademicYear $academicYear;

    protected function setUp(): void
    {
        parent::setUp();

        $this->guruUser = User::factory()->create(['role' => UserRole::Guru]);
        $this->teacher = Teacher::factory()->create(['user_id' => $this->guruUser->id]);
        $this->academicYear = AcademicYear::factory()->create(['is_active' => true]);
    }

    public function test_guru_can_view_homeroom_consultation_index(): void
    {
        $counselor = User::factory()->create(['role' => UserRole::GuruBk]);
        $student = Student::factory()->create();

        HomeroomConsultation::factory()->create([
            'teacher_id' => $this->teacher->id,
            'counselor_id' => $counselor->id,
            'student_id' => $student->id,
            'academic_year_id' => $this->academicYear->id,
        ]);

        $response = $this->actingAs($this->guruUser)->get(route('guru.homeroom-consultations.index'));

        $response->assertOk();
        $response->assertViewIs('guru.homeroom-consultations.index');
        $response->assertViewHas('consultations');
    }

    public function test_guru_can_view_homeroom_consultation_detail(): void
    {
        $counselor = User::factory()->create(['role' => UserRole::GuruBk]);
        $student = Student::factory()->create();

        $consultation = HomeroomConsultation::factory()->create([
            'teacher_id' => $this->teacher->id,
            'counselor_id' => $counselor->id,
            'student_id' => $student->id,
            'academic_year_id' => $this->academicYear->id,
        ]);

        $response = $this->actingAs($this->guruUser)->get(route('guru.homeroom-consultations.show', $consultation));

        $response->assertOk();
        $response->assertViewIs('guru.homeroom-consultations.show');
    }

    public function test_guru_cannot_view_other_teacher_homeroom_consultation(): void
    {
        $otherGuruUser = User::factory()->create(['role' => UserRole::Guru]);
        $otherTeacher = Teacher::factory()->create(['user_id' => $otherGuruUser->id]);
        $counselor = User::factory()->create(['role' => UserRole::GuruBk]);
        $student = Student::factory()->create();

        $consultation = HomeroomConsultation::factory()->create([
            'teacher_id' => $otherTeacher->id,
            'counselor_id' => $counselor->id,
            'student_id' => $student->id,
            'academic_year_id' => $this->academicYear->id,
        ]);

        $response = $this->actingAs($this->guruUser)->get(route('guru.homeroom-consultations.show', $consultation));

        $response->assertForbidden();
    }

    public function test_guru_can_view_subject_consultation_index(): void
    {
        $counselor = User::factory()->create(['role' => UserRole::GuruBk]);
        $student = Student::factory()->create();

        SubjectTeacherConsultation::factory()->create([
            'teacher_id' => $this->teacher->id,
            'counselor_id' => $counselor->id,
            'student_id' => $student->id,
            'academic_year_id' => $this->academicYear->id,
        ]);

        $response = $this->actingAs($this->guruUser)->get(route('guru.subject-consultations.index'));

        $response->assertOk();
        $response->assertViewIs('guru.subject-consultations.index');
    }

    public function test_guru_can_view_subject_consultation_detail(): void
    {
        $counselor = User::factory()->create(['role' => UserRole::GuruBk]);
        $student = Student::factory()->create();

        $consultation = SubjectTeacherConsultation::factory()->create([
            'teacher_id' => $this->teacher->id,
            'counselor_id' => $counselor->id,
            'student_id' => $student->id,
            'academic_year_id' => $this->academicYear->id,
        ]);

        $response = $this->actingAs($this->guruUser)->get(route('guru.subject-consultations.show', $consultation));

        $response->assertOk();
        $response->assertViewIs('guru.subject-consultations.show');
    }

    public function test_guru_cannot_view_other_teacher_subject_consultation(): void
    {
        $otherGuruUser = User::factory()->create(['role' => UserRole::Guru]);
        $otherTeacher = Teacher::factory()->create(['user_id' => $otherGuruUser->id]);
        $counselor = User::factory()->create(['role' => UserRole::GuruBk]);
        $student = Student::factory()->create();

        $consultation = SubjectTeacherConsultation::factory()->create([
            'teacher_id' => $otherTeacher->id,
            'counselor_id' => $counselor->id,
            'student_id' => $student->id,
            'academic_year_id' => $this->academicYear->id,
        ]);

        $response = $this->actingAs($this->guruUser)->get(route('guru.subject-consultations.show', $consultation));

        $response->assertForbidden();
    }

    public function test_guru_can_view_classroom_index(): void
    {
        $classroom = Classroom::factory()->create([
            'homeroom_teacher_id' => $this->teacher->id,
            'academic_year_id' => $this->academicYear->id,
        ]);

        $student = Student::factory()->create();
        $classroom->students()->attach($student->id);

        $response = $this->actingAs($this->guruUser)->get(route('guru.classrooms.index'));

        $response->assertOk();
        $response->assertViewIs('guru.classrooms.index');
        $response->assertViewHas('classrooms');
        $response->assertSee($classroom->name);
    }

    public function test_non_guru_cannot_access_guru_routes(): void
    {
        $siswaUser = User::factory()->create(['role' => UserRole::Siswa]);

        $response = $this->actingAs($siswaUser)->get(route('guru.homeroom-consultations.index'));

        $response->assertForbidden();
    }
}
