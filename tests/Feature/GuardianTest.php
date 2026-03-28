<?php

namespace Tests\Feature;

use App\Models\Guardian;
use App\Models\ParentConsultation;
use App\Models\Student;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GuardianTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_view_guardians_index(): void
    {
        $admin = User::factory()->admin()->create();
        Guardian::factory()->count(3)->create();

        $response = $this->actingAs($admin)->get(route('admin.guardians.index'));

        $response->assertStatus(200);
        $response->assertViewIs('guardians.index');
    }

    public function test_admin_can_search_guardians(): void
    {
        $admin = User::factory()->admin()->create();
        Guardian::factory()->create(['full_name' => 'Budi Santoso']);
        Guardian::factory()->create(['full_name' => 'Siti Aminah']);

        $response = $this->actingAs($admin)->get(route('admin.guardians.index', ['search' => 'Budi']));

        $response->assertStatus(200);
        $response->assertSee('Budi Santoso');
    }

    public function test_admin_can_create_guardian(): void
    {
        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin)->post(route('admin.guardians.store'), [
            'full_name' => 'Budi Santoso',
            'email' => 'budi.santoso@contoh.com',
            'phone' => '081234567890',
            'occupation' => 'Wiraswasta',
            'address' => 'Jl. Merdeka No. 1',
        ]);

        $response->assertRedirect(route('admin.guardians.index'));
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('guardians', ['full_name' => 'Budi Santoso']);
        $this->assertDatabaseHas('users', ['email' => 'budi.santoso@contoh.com']);
    }

    public function test_admin_can_create_guardian_with_students(): void
    {
        $admin = User::factory()->admin()->create();
        $student = Student::factory()->create();

        $response = $this->actingAs($admin)->post(route('admin.guardians.store'), [
            'full_name' => 'Budi Santoso',
            'email' => 'budi.santoso@contoh.com',
            'students' => [
                ['student_id' => $student->id, 'relationship' => 'ayah'],
            ],
        ]);

        $response->assertRedirect(route('admin.guardians.index'));
        $this->assertDatabaseHas('guardian_student', [
            'student_id' => $student->id,
            'relationship' => 'ayah',
        ]);
    }

    public function test_admin_can_update_guardian(): void
    {
        $admin = User::factory()->admin()->create();
        $guardian = Guardian::factory()->create(['full_name' => 'Budi']);

        $response = $this->actingAs($admin)->put(route('admin.guardians.update', $guardian), [
            'full_name' => 'Budi Updated',
            'email' => $guardian->user->email,
        ]);

        $response->assertRedirect(route('admin.guardians.index'));
        $this->assertDatabaseHas('guardians', ['id' => $guardian->id, 'full_name' => 'Budi Updated']);
    }

    public function test_admin_can_delete_guardian_without_consultations(): void
    {
        $admin = User::factory()->admin()->create();
        $guardian = Guardian::factory()->create();
        $userId = $guardian->user_id;

        $response = $this->actingAs($admin)->delete(route('admin.guardians.destroy', $guardian));

        $response->assertRedirect(route('admin.guardians.index'));
        $response->assertSessionHas('success');
        $this->assertDatabaseMissing('guardians', ['id' => $guardian->id]);
        $this->assertDatabaseMissing('users', ['id' => $userId]);
    }

    public function test_admin_cannot_delete_guardian_with_consultations(): void
    {
        $admin = User::factory()->admin()->create();
        $guardian = Guardian::factory()->create();
        ParentConsultation::factory()->create(['guardian_id' => $guardian->id]);

        $response = $this->actingAs($admin)->delete(route('admin.guardians.destroy', $guardian));

        $response->assertRedirect(route('admin.guardians.index'));
        $response->assertSessionHas('error');
        $this->assertDatabaseHas('guardians', ['id' => $guardian->id]);
    }

    public function test_non_admin_cannot_access_guardians(): void
    {
        $siswa = User::factory()->siswa()->create();

        $response = $this->actingAs($siswa)->get(route('admin.guardians.index'));

        $response->assertStatus(403);
    }
}
