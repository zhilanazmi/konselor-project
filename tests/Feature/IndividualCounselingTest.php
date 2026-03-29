<?php

namespace Tests\Feature;

use App\Models\AcademicYear;
use App\Models\IndividualCounseling;
use App\Models\Student;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class IndividualCounselingTest extends TestCase
{
    use RefreshDatabase;

    public function test_guru_bk_can_view_individual_counselings_index(): void
    {
        $guruBk = User::factory()->guruBk()->create();
        $student = Student::factory()->create();
        IndividualCounseling::factory()->count(3)->create(['counselor_id' => $guruBk->id, 'student_id' => $student->id]);

        $response = $this->actingAs($guruBk)->get(route('guru-bk.individual-counselings.index'));

        $response->assertStatus(200);
        $response->assertViewIs('individual-counselings.index');
    }

    public function test_guru_bk_can_filter_by_status(): void
    {
        $guruBk = User::factory()->guruBk()->create();
        $student = Student::factory()->create();
        IndividualCounseling::factory()->create(['counselor_id' => $guruBk->id, 'student_id' => $student->id, 'status' => 'completed']);
        IndividualCounseling::factory()->create(['counselor_id' => $guruBk->id, 'student_id' => $student->id, 'status' => 'scheduled']);

        $response = $this->actingAs($guruBk)->get(route('guru-bk.individual-counselings.index', ['status' => 'completed']));

        $response->assertStatus(200);
    }

    public function test_guru_bk_can_view_create_form(): void
    {
        $guruBk = User::factory()->guruBk()->create();

        $response = $this->actingAs($guruBk)->get(route('guru-bk.individual-counselings.create'));

        $response->assertStatus(200);
        $response->assertViewIs('individual-counselings.create');
    }

    public function test_guru_bk_can_create_individual_counseling(): void
    {
        $guruBk = User::factory()->guruBk()->create();
        $student = Student::factory()->create();
        $academicYear = AcademicYear::factory()->create();

        $response = $this->actingAs($guruBk)->post(route('guru-bk.individual-counselings.store'), [
            'student_id' => $student->id,
            'academic_year_id' => $academicYear->id,
            'scheduled_at' => '2026-04-01 09:00',
            'status' => 'scheduled',
            'category' => 'belajar',
            'problem_description' => 'Siswa mengalami kesulitan dalam memahami mata pelajaran matematika.',
            'approach' => null,
            'result' => null,
            'follow_up_plan' => null,
        ]);

        $response->assertRedirect(route('guru-bk.individual-counselings.index'));
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('individual_counselings', [
            'student_id' => $student->id,
            'counselor_id' => $guruBk->id,
            'category' => 'belajar',
            'status' => 'scheduled',
        ]);
    }

    public function test_store_validates_required_fields(): void
    {
        $guruBk = User::factory()->guruBk()->create();

        $response = $this->actingAs($guruBk)->post(route('guru-bk.individual-counselings.store'), []);

        $response->assertSessionHasErrors(['student_id', 'academic_year_id', 'scheduled_at', 'status', 'category', 'problem_description']);
    }

    public function test_guru_bk_can_view_individual_counseling_detail(): void
    {
        $guruBk = User::factory()->guruBk()->create();
        $counseling = IndividualCounseling::factory()->create(['counselor_id' => $guruBk->id]);

        $response = $this->actingAs($guruBk)->get(route('guru-bk.individual-counselings.show', $counseling));

        $response->assertStatus(200);
        $response->assertViewIs('individual-counselings.show');
    }

    public function test_guru_bk_can_view_edit_form(): void
    {
        $guruBk = User::factory()->guruBk()->create();
        $counseling = IndividualCounseling::factory()->create(['counselor_id' => $guruBk->id]);

        $response = $this->actingAs($guruBk)->get(route('guru-bk.individual-counselings.edit', $counseling));

        $response->assertStatus(200);
        $response->assertViewIs('individual-counselings.edit');
    }

    public function test_guru_bk_can_update_individual_counseling(): void
    {
        $guruBk = User::factory()->guruBk()->create();
        $student = Student::factory()->create();
        $academicYear = AcademicYear::factory()->create();
        $counseling = IndividualCounseling::factory()->create([
            'counselor_id' => $guruBk->id,
            'student_id' => $student->id,
            'academic_year_id' => $academicYear->id,
            'status' => 'scheduled',
        ]);

        $response = $this->actingAs($guruBk)->put(route('guru-bk.individual-counselings.update', $counseling), [
            'student_id' => $student->id,
            'academic_year_id' => $academicYear->id,
            'scheduled_at' => '2026-04-02 10:00',
            'status' => 'completed',
            'category' => 'sosial',
            'problem_description' => 'Permasalahan sosial dengan teman sebaya.',
            'result' => 'Siswa sudah memahami cara berinteraksi yang baik.',
            'approach' => null,
            'follow_up_plan' => null,
        ]);

        $response->assertRedirect(route('guru-bk.individual-counselings.show', $counseling));
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('individual_counselings', [
            'id' => $counseling->id,
            'status' => 'completed',
            'category' => 'sosial',
        ]);
    }

    public function test_guru_bk_can_delete_individual_counseling(): void
    {
        $guruBk = User::factory()->guruBk()->create();
        $counseling = IndividualCounseling::factory()->create(['counselor_id' => $guruBk->id]);

        $response = $this->actingAs($guruBk)->delete(route('guru-bk.individual-counselings.destroy', $counseling));

        $response->assertRedirect(route('guru-bk.individual-counselings.index'));
        $response->assertSessionHas('success');
        $this->assertDatabaseMissing('individual_counselings', ['id' => $counseling->id]);
    }

    public function test_non_guru_bk_cannot_access_individual_counselings(): void
    {
        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin)->get(route('guru-bk.individual-counselings.index'));

        $response->assertStatus(403);
    }

    public function test_guest_cannot_access_individual_counselings(): void
    {
        $response = $this->get(route('guru-bk.individual-counselings.index'));

        $response->assertRedirect(route('login'));
    }
}
