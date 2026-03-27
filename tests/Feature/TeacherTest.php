<?php

namespace Tests\Feature;

use App\Models\AcademicYear;
use App\Models\Classroom;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TeacherTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_view_teachers_index(): void
    {
        $admin = User::factory()->admin()->create();
        Teacher::factory()->count(3)->create();

        $response = $this->actingAs($admin)->get(route('admin.teachers.index'));

        $response->assertStatus(200);
        $response->assertViewIs('teachers.index');
    }

    public function test_admin_can_search_teachers(): void
    {
        $admin = User::factory()->admin()->create();
        Teacher::factory()->create(['full_name' => 'Budi Santoso', 'nip' => '123456789012345678']);
        Teacher::factory()->create(['full_name' => 'Siti Aminah', 'nip' => '987654321098765432']);

        $response = $this->actingAs($admin)->get(route('admin.teachers.index', ['search' => 'Budi']));

        $response->assertStatus(200);
        $response->assertSee('Budi Santoso');
    }

    public function test_admin_can_create_teacher(): void
    {
        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin)->post(route('admin.teachers.store'), [
            'nip' => '199001012024011001',
            'full_name' => 'Ahmad Fauzi',
            'email' => 'ahmad.fauzi@sekolah.sch.id',
            'subject' => 'Matematika',
        ]);

        $response->assertRedirect(route('admin.teachers.index'));
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('teachers', ['nip' => '199001012024011001', 'full_name' => 'Ahmad Fauzi']);
        $this->assertDatabaseHas('users', ['email' => 'ahmad.fauzi@sekolah.sch.id']);
    }

    public function test_admin_can_update_teacher(): void
    {
        $admin = User::factory()->admin()->create();
        $teacher = Teacher::factory()->create(['full_name' => 'Ahmad']);

        $response = $this->actingAs($admin)->put(route('admin.teachers.update', $teacher), [
            'nip' => $teacher->nip,
            'full_name' => 'Ahmad Updated',
            'email' => $teacher->user->email,
            'subject' => 'IPA',
        ]);

        $response->assertRedirect(route('admin.teachers.index'));
        $this->assertDatabaseHas('teachers', ['id' => $teacher->id, 'full_name' => 'Ahmad Updated']);
    }

    public function test_admin_can_delete_teacher_without_relations(): void
    {
        $admin = User::factory()->admin()->create();
        $teacher = Teacher::factory()->create();
        $userId = $teacher->user_id;

        $response = $this->actingAs($admin)->delete(route('admin.teachers.destroy', $teacher));

        $response->assertRedirect(route('admin.teachers.index'));
        $response->assertSessionHas('success');
        $this->assertDatabaseMissing('teachers', ['id' => $teacher->id]);
        $this->assertDatabaseMissing('users', ['id' => $userId]);
    }

    public function test_admin_cannot_delete_teacher_with_homeroom_class(): void
    {
        $admin = User::factory()->admin()->create();
        $teacher = Teacher::factory()->create();
        $academicYear = AcademicYear::factory()->create(['is_active' => true]);
        Classroom::factory()->create([
            'homeroom_teacher_id' => $teacher->id,
            'academic_year_id' => $academicYear->id,
        ]);

        $response = $this->actingAs($admin)->delete(route('admin.teachers.destroy', $teacher));

        $response->assertRedirect(route('admin.teachers.index'));
        $response->assertSessionHas('error');
        $this->assertDatabaseHas('teachers', ['id' => $teacher->id]);
    }

    public function test_validation_nip_unique(): void
    {
        $admin = User::factory()->admin()->create();
        Teacher::factory()->create(['nip' => '199001012024011001']);

        $response = $this->actingAs($admin)->post(route('admin.teachers.store'), [
            'nip' => '199001012024011001',
            'full_name' => 'Another Teacher',
            'email' => 'another@sekolah.sch.id',
        ]);

        $response->assertSessionHasErrors(['nip']);
    }

    public function test_non_admin_cannot_access_teachers(): void
    {
        $siswa = User::factory()->siswa()->create();

        $response = $this->actingAs($siswa)->get(route('admin.teachers.index'));

        $response->assertStatus(403);
    }
}
