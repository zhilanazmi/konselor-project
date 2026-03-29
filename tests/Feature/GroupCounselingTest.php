<?php

namespace Tests\Feature;

use App\Models\AcademicYear;
use App\Models\GroupCounseling;
use App\Models\Student;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GroupCounselingTest extends TestCase
{
    use RefreshDatabase;

    public function test_guru_bk_can_view_group_counselings_index(): void
    {
        $guruBk = User::factory()->guruBk()->create();
        GroupCounseling::factory()->count(3)->create(['counselor_id' => $guruBk->id]);

        $response = $this->actingAs($guruBk)->get(route('guru-bk.group-counselings.index'));

        $response->assertStatus(200);
        $response->assertViewIs('group-counselings.index');
    }

    public function test_guru_bk_can_filter_by_status(): void
    {
        $guruBk = User::factory()->guruBk()->create();
        GroupCounseling::factory()->create(['counselor_id' => $guruBk->id, 'status' => 'completed']);
        GroupCounseling::factory()->create(['counselor_id' => $guruBk->id, 'status' => 'scheduled']);

        $response = $this->actingAs($guruBk)->get(route('guru-bk.group-counselings.index', ['status' => 'completed']));

        $response->assertStatus(200);
    }

    public function test_guru_bk_can_view_create_form(): void
    {
        $guruBk = User::factory()->guruBk()->create();

        $response = $this->actingAs($guruBk)->get(route('guru-bk.group-counselings.create'));

        $response->assertStatus(200);
        $response->assertViewIs('group-counselings.create');
    }

    public function test_guru_bk_can_create_group_counseling_without_participants(): void
    {
        $guruBk = User::factory()->guruBk()->create();
        $academicYear = AcademicYear::factory()->create();

        $response = $this->actingAs($guruBk)->post(route('guru-bk.group-counselings.store'), [
            'academic_year_id' => $academicYear->id,
            'topic' => 'Manajemen Waktu Belajar',
            'scheduled_at' => '2026-04-05 08:00',
            'status' => 'scheduled',
            'description' => 'Membantu siswa mengelola waktu belajar.',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('group_counselings', [
            'topic' => 'Manajemen Waktu Belajar',
            'counselor_id' => $guruBk->id,
            'status' => 'scheduled',
        ]);
    }

    public function test_guru_bk_can_create_group_counseling_with_participants(): void
    {
        $guruBk = User::factory()->guruBk()->create();
        $academicYear = AcademicYear::factory()->create();
        $students = Student::factory()->count(3)->create();

        $response = $this->actingAs($guruBk)->post(route('guru-bk.group-counselings.store'), [
            'academic_year_id' => $academicYear->id,
            'topic' => 'Motivasi Belajar',
            'scheduled_at' => '2026-04-06 09:00',
            'status' => 'scheduled',
            'student_ids' => $students->pluck('id')->toArray(),
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $counseling = GroupCounseling::query()->where('topic', 'Motivasi Belajar')->first();
        $this->assertCount(3, $counseling->participants);
    }

    public function test_store_validates_required_fields(): void
    {
        $guruBk = User::factory()->guruBk()->create();

        $response = $this->actingAs($guruBk)->post(route('guru-bk.group-counselings.store'), []);

        $response->assertSessionHasErrors(['academic_year_id', 'topic', 'scheduled_at', 'status']);
    }

    public function test_guru_bk_can_view_group_counseling_detail(): void
    {
        $guruBk = User::factory()->guruBk()->create();
        $counseling = GroupCounseling::factory()->create(['counselor_id' => $guruBk->id]);

        $response = $this->actingAs($guruBk)->get(route('guru-bk.group-counselings.show', $counseling));

        $response->assertStatus(200);
        $response->assertViewIs('group-counselings.show');
    }

    public function test_guru_bk_can_update_group_counseling(): void
    {
        $guruBk = User::factory()->guruBk()->create();
        $academicYear = AcademicYear::factory()->create();
        $counseling = GroupCounseling::factory()->create([
            'counselor_id' => $guruBk->id,
            'academic_year_id' => $academicYear->id,
            'status' => 'scheduled',
        ]);

        $response = $this->actingAs($guruBk)->put(route('guru-bk.group-counselings.update', $counseling), [
            'academic_year_id' => $academicYear->id,
            'topic' => 'Topik Diperbarui',
            'scheduled_at' => '2026-04-07 10:00',
            'status' => 'completed',
            'result' => 'Sesi berjalan lancar.',
        ]);

        $response->assertRedirect(route('guru-bk.group-counselings.show', $counseling));
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('group_counselings', [
            'id' => $counseling->id,
            'topic' => 'Topik Diperbarui',
            'status' => 'completed',
        ]);
    }

    public function test_guru_bk_can_add_participant(): void
    {
        $guruBk = User::factory()->guruBk()->create();
        $counseling = GroupCounseling::factory()->create(['counselor_id' => $guruBk->id]);
        $student = Student::factory()->create();

        $response = $this->actingAs($guruBk)->post(
            route('guru-bk.group-counselings.participants.store', $counseling),
            ['student_id' => $student->id]
        );

        $response->assertRedirect();
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('group_counseling_participants', [
            'group_counseling_id' => $counseling->id,
            'student_id' => $student->id,
        ]);
    }

    public function test_cannot_add_duplicate_participant(): void
    {
        $guruBk = User::factory()->guruBk()->create();
        $counseling = GroupCounseling::factory()->create(['counselor_id' => $guruBk->id]);
        $student = Student::factory()->create();
        $counseling->participants()->attach($student->id);

        $response = $this->actingAs($guruBk)->post(
            route('guru-bk.group-counselings.participants.store', $counseling),
            ['student_id' => $student->id]
        );

        $response->assertRedirect();
        $response->assertSessionHas('error');
        $this->assertCount(1, $counseling->fresh()->participants);
    }

    public function test_guru_bk_can_remove_participant(): void
    {
        $guruBk = User::factory()->guruBk()->create();
        $counseling = GroupCounseling::factory()->create(['counselor_id' => $guruBk->id]);
        $student = Student::factory()->create();
        $counseling->participants()->attach($student->id);

        $response = $this->actingAs($guruBk)->delete(
            route('guru-bk.group-counselings.participants.destroy', [$counseling, $student])
        );

        $response->assertRedirect();
        $response->assertSessionHas('success');
        $this->assertDatabaseMissing('group_counseling_participants', [
            'group_counseling_id' => $counseling->id,
            'student_id' => $student->id,
        ]);
    }

    public function test_guru_bk_can_update_participant_notes(): void
    {
        $guruBk = User::factory()->guruBk()->create();
        $counseling = GroupCounseling::factory()->create(['counselor_id' => $guruBk->id]);
        $student = Student::factory()->create();
        $counseling->participants()->attach($student->id, ['notes' => null]);

        $response = $this->actingAs($guruBk)->patch(
            route('guru-bk.group-counselings.participants.update-notes', [$counseling, $student]),
            ['notes' => 'Siswa aktif berpartisipasi.']
        );

        $response->assertRedirect();
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('group_counseling_participants', [
            'group_counseling_id' => $counseling->id,
            'student_id' => $student->id,
            'notes' => 'Siswa aktif berpartisipasi.',
        ]);
    }

    public function test_guru_bk_can_delete_group_counseling(): void
    {
        $guruBk = User::factory()->guruBk()->create();
        $counseling = GroupCounseling::factory()->create(['counselor_id' => $guruBk->id]);

        $response = $this->actingAs($guruBk)->delete(route('guru-bk.group-counselings.destroy', $counseling));

        $response->assertRedirect(route('guru-bk.group-counselings.index'));
        $response->assertSessionHas('success');
        $this->assertDatabaseMissing('group_counselings', ['id' => $counseling->id]);
    }

    public function test_non_guru_bk_cannot_access_group_counselings(): void
    {
        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin)->get(route('guru-bk.group-counselings.index'));

        $response->assertStatus(403);
    }

    public function test_guest_cannot_access_group_counselings(): void
    {
        $response = $this->get(route('guru-bk.group-counselings.index'));

        $response->assertRedirect(route('login'));
    }
}
