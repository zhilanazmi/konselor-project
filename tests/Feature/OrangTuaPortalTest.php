<?php

namespace Tests\Feature;

use App\Enums\UserRole;
use App\Models\AcademicYear;
use App\Models\Guardian;
use App\Models\IndividualCounseling;
use App\Models\ParentConsultation;
use App\Models\Student;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrangTuaPortalTest extends TestCase
{
    use RefreshDatabase;

    private User $orangTuaUser;

    private Guardian $guardian;

    private Student $child;

    private AcademicYear $academicYear;

    protected function setUp(): void
    {
        parent::setUp();

        $this->orangTuaUser = User::factory()->create(['role' => UserRole::OrangTua]);
        $this->guardian = Guardian::factory()->create(['user_id' => $this->orangTuaUser->id]);
        $this->child = Student::factory()->create();
        $this->guardian->students()->attach($this->child->id, ['relationship' => 'ayah']);
        $this->academicYear = AcademicYear::factory()->create(['is_active' => true]);
    }

    public function test_orang_tua_can_view_children_index(): void
    {
        $response = $this->actingAs($this->orangTuaUser)->get(route('orang-tua.children.index'));

        $response->assertOk();
        $response->assertViewIs('orang-tua.children.index');
        $response->assertViewHas('children');
        $response->assertSee($this->child->full_name);
    }

    public function test_orang_tua_can_view_child_counselings(): void
    {
        $counselor = User::factory()->create(['role' => UserRole::GuruBk]);

        IndividualCounseling::factory()->create([
            'student_id' => $this->child->id,
            'counselor_id' => $counselor->id,
            'academic_year_id' => $this->academicYear->id,
        ]);

        $response = $this->actingAs($this->orangTuaUser)->get(route('orang-tua.children.counselings', $this->child));

        $response->assertOk();
        $response->assertViewIs('orang-tua.children.counselings');
        $response->assertViewHas('counselings');
    }

    public function test_orang_tua_can_view_child_counseling_detail(): void
    {
        $counselor = User::factory()->create(['role' => UserRole::GuruBk]);

        $counseling = IndividualCounseling::factory()->create([
            'student_id' => $this->child->id,
            'counselor_id' => $counselor->id,
            'academic_year_id' => $this->academicYear->id,
        ]);

        $response = $this->actingAs($this->orangTuaUser)->get(route('orang-tua.children.counseling-show', [$this->child, $counseling]));

        $response->assertOk();
        $response->assertViewIs('orang-tua.children.counseling-show');
    }

    public function test_orang_tua_cannot_view_other_child_counselings(): void
    {
        $otherChild = Student::factory()->create();

        $response = $this->actingAs($this->orangTuaUser)->get(route('orang-tua.children.counselings', $otherChild));

        $response->assertForbidden();
    }

    public function test_orang_tua_cannot_view_other_child_counseling_detail(): void
    {
        $otherChild = Student::factory()->create();
        $counselor = User::factory()->create(['role' => UserRole::GuruBk]);

        $counseling = IndividualCounseling::factory()->create([
            'student_id' => $otherChild->id,
            'counselor_id' => $counselor->id,
            'academic_year_id' => $this->academicYear->id,
        ]);

        $response = $this->actingAs($this->orangTuaUser)->get(route('orang-tua.children.counseling-show', [$otherChild, $counseling]));

        $response->assertForbidden();
    }

    public function test_orang_tua_can_view_consultation_index(): void
    {
        $counselor = User::factory()->create(['role' => UserRole::GuruBk]);

        ParentConsultation::factory()->create([
            'guardian_id' => $this->guardian->id,
            'student_id' => $this->child->id,
            'counselor_id' => $counselor->id,
            'academic_year_id' => $this->academicYear->id,
        ]);

        $response = $this->actingAs($this->orangTuaUser)->get(route('orang-tua.consultations.index'));

        $response->assertOk();
        $response->assertViewIs('orang-tua.consultations.index');
        $response->assertViewHas('consultations');
    }

    public function test_orang_tua_can_view_consultation_detail(): void
    {
        $counselor = User::factory()->create(['role' => UserRole::GuruBk]);

        $consultation = ParentConsultation::factory()->create([
            'guardian_id' => $this->guardian->id,
            'student_id' => $this->child->id,
            'counselor_id' => $counselor->id,
            'academic_year_id' => $this->academicYear->id,
        ]);

        $response = $this->actingAs($this->orangTuaUser)->get(route('orang-tua.consultations.show', $consultation));

        $response->assertOk();
        $response->assertViewIs('orang-tua.consultations.show');
    }

    public function test_orang_tua_cannot_view_other_guardian_consultation(): void
    {
        $otherOrangTua = User::factory()->create(['role' => UserRole::OrangTua]);
        $otherGuardian = Guardian::factory()->create(['user_id' => $otherOrangTua->id]);
        $counselor = User::factory()->create(['role' => UserRole::GuruBk]);

        $consultation = ParentConsultation::factory()->create([
            'guardian_id' => $otherGuardian->id,
            'student_id' => $this->child->id,
            'counselor_id' => $counselor->id,
            'academic_year_id' => $this->academicYear->id,
        ]);

        $response = $this->actingAs($this->orangTuaUser)->get(route('orang-tua.consultations.show', $consultation));

        $response->assertForbidden();
    }

    public function test_non_orang_tua_cannot_access_orang_tua_routes(): void
    {
        $siswaUser = User::factory()->create(['role' => UserRole::Siswa]);

        $response = $this->actingAs($siswaUser)->get(route('orang-tua.children.index'));

        $response->assertForbidden();
    }
}
