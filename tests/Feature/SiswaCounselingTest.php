<?php

namespace Tests\Feature;

use App\Enums\UserRole;
use App\Models\AcademicYear;
use App\Models\GroupCounseling;
use App\Models\IndividualCounseling;
use App\Models\Student;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SiswaCounselingTest extends TestCase
{
    use RefreshDatabase;

    private User $siswaUser;

    private Student $student;

    private AcademicYear $academicYear;

    protected function setUp(): void
    {
        parent::setUp();

        $this->siswaUser = User::factory()->create(['role' => UserRole::Siswa]);
        $this->student = Student::factory()->create(['user_id' => $this->siswaUser->id]);
        $this->academicYear = AcademicYear::factory()->create(['is_active' => true]);
    }

    public function test_siswa_can_view_counseling_index(): void
    {
        $counselor = User::factory()->create(['role' => UserRole::GuruBk]);

        IndividualCounseling::factory()->create([
            'student_id' => $this->student->id,
            'counselor_id' => $counselor->id,
            'academic_year_id' => $this->academicYear->id,
        ]);

        $response = $this->actingAs($this->siswaUser)->get(route('siswa.counselings.index'));

        $response->assertOk();
        $response->assertViewIs('siswa.counselings.index');
        $response->assertViewHas('counselings');
    }

    public function test_siswa_can_view_own_counseling_detail(): void
    {
        $counselor = User::factory()->create(['role' => UserRole::GuruBk]);

        $counseling = IndividualCounseling::factory()->create([
            'student_id' => $this->student->id,
            'counselor_id' => $counselor->id,
            'academic_year_id' => $this->academicYear->id,
        ]);

        $response = $this->actingAs($this->siswaUser)->get(route('siswa.counselings.show', $counseling));

        $response->assertOk();
        $response->assertViewIs('siswa.counselings.show');
    }

    public function test_siswa_cannot_view_other_student_counseling_detail(): void
    {
        $otherSiswa = User::factory()->create(['role' => UserRole::Siswa]);
        $otherStudent = Student::factory()->create(['user_id' => $otherSiswa->id]);
        $counselor = User::factory()->create(['role' => UserRole::GuruBk]);

        $counseling = IndividualCounseling::factory()->create([
            'student_id' => $otherStudent->id,
            'counselor_id' => $counselor->id,
            'academic_year_id' => $this->academicYear->id,
        ]);

        $response = $this->actingAs($this->siswaUser)->get(route('siswa.counselings.show', $counseling));

        $response->assertForbidden();
    }

    public function test_siswa_can_view_group_counseling_index(): void
    {
        $counselor = User::factory()->create(['role' => UserRole::GuruBk]);

        $gc = GroupCounseling::factory()->create([
            'counselor_id' => $counselor->id,
            'academic_year_id' => $this->academicYear->id,
        ]);
        $gc->participants()->attach($this->student->id);

        $response = $this->actingAs($this->siswaUser)->get(route('siswa.group-counselings.index'));

        $response->assertOk();
        $response->assertViewIs('siswa.group-counselings.index');
    }

    public function test_siswa_can_view_group_counseling_show_as_participant(): void
    {
        $counselor = User::factory()->create(['role' => UserRole::GuruBk]);

        $gc = GroupCounseling::factory()->create([
            'counselor_id' => $counselor->id,
            'academic_year_id' => $this->academicYear->id,
        ]);
        $gc->participants()->attach($this->student->id, ['notes' => 'Catatan untuk siswa ini']);

        $response = $this->actingAs($this->siswaUser)->get(route('siswa.group-counselings.show', $gc));

        $response->assertOk();
        $response->assertViewIs('siswa.group-counselings.show');
        $response->assertViewHas('myNotes', 'Catatan untuk siswa ini');
    }

    public function test_siswa_cannot_view_group_counseling_if_not_participant(): void
    {
        $counselor = User::factory()->create(['role' => UserRole::GuruBk]);

        $gc = GroupCounseling::factory()->create([
            'counselor_id' => $counselor->id,
            'academic_year_id' => $this->academicYear->id,
        ]);

        $response = $this->actingAs($this->siswaUser)->get(route('siswa.group-counselings.show', $gc));

        $response->assertForbidden();
    }

    public function test_non_siswa_cannot_access_siswa_counseling_routes(): void
    {
        $guruUser = User::factory()->create(['role' => UserRole::Guru]);

        $response = $this->actingAs($guruUser)->get(route('siswa.counselings.index'));

        $response->assertForbidden();
    }
}
