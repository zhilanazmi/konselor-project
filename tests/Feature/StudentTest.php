<?php

namespace Tests\Feature;

use App\Models\IndividualCounseling;
use App\Models\Student;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StudentTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_view_students_index(): void
    {
        $admin = User::factory()->admin()->create();
        Student::factory()->count(3)->create();

        $response = $this->actingAs($admin)->get(route('admin.students.index'));

        $response->assertStatus(200);
        $response->assertViewIs('students.index');
    }

    public function test_admin_can_search_students(): void
    {
        $admin = User::factory()->admin()->create();
        Student::factory()->create(['full_name' => 'Budi Santoso', 'nis' => '12345678']);
        Student::factory()->create(['full_name' => 'Siti Aminah', 'nis' => '87654321']);

        $response = $this->actingAs($admin)->get(route('admin.students.index', ['search' => 'Budi']));

        $response->assertStatus(200);
        $response->assertSee('Budi Santoso');
    }

    public function test_admin_can_create_student(): void
    {
        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin)->post(route('admin.students.store'), [
            'nis' => '12345678',
            'full_name' => 'Budi Santoso',
            'gender' => 'L',
            'birth_date' => '2012-05-15',
            'birth_place' => 'Jakarta',
            'address' => 'Jl. Merdeka No. 1',
            'phone' => '081234567890',
        ]);

        $response->assertRedirect(route('admin.students.index'));
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('students', ['nis' => '12345678', 'full_name' => 'Budi Santoso']);
        $this->assertDatabaseHas('users', ['email' => '12345678@siswa.konselorkita.test']);
    }

    public function test_admin_can_update_student(): void
    {
        $admin = User::factory()->admin()->create();
        $student = Student::factory()->create(['full_name' => 'Budi']);

        $response = $this->actingAs($admin)->put(route('admin.students.update', $student), [
            'nis' => $student->nis,
            'full_name' => 'Budi Updated',
            'gender' => 'L',
        ]);

        $response->assertRedirect(route('admin.students.index'));
        $this->assertDatabaseHas('students', ['id' => $student->id, 'full_name' => 'Budi Updated']);
    }

    public function test_admin_can_delete_student_without_counseling(): void
    {
        $admin = User::factory()->admin()->create();
        $student = Student::factory()->create();
        $userId = $student->user_id;

        $response = $this->actingAs($admin)->delete(route('admin.students.destroy', $student));

        $response->assertRedirect(route('admin.students.index'));
        $response->assertSessionHas('success');
        $this->assertDatabaseMissing('students', ['id' => $student->id]);
        $this->assertDatabaseMissing('users', ['id' => $userId]);
    }

    public function test_admin_cannot_delete_student_with_counseling(): void
    {
        $admin = User::factory()->admin()->create();
        $student = Student::factory()->create();
        IndividualCounseling::factory()->create(['student_id' => $student->id]);

        $response = $this->actingAs($admin)->delete(route('admin.students.destroy', $student));

        $response->assertRedirect(route('admin.students.index'));
        $response->assertSessionHas('error');
        $this->assertDatabaseHas('students', ['id' => $student->id]);
    }

    public function test_validation_nis_unique(): void
    {
        $admin = User::factory()->admin()->create();
        Student::factory()->create(['nis' => '12345678']);

        $response = $this->actingAs($admin)->post(route('admin.students.store'), [
            'nis' => '12345678',
            'full_name' => 'Another Student',
            'gender' => 'P',
        ]);

        $response->assertSessionHasErrors(['nis']);
    }

    public function test_non_admin_cannot_access_students(): void
    {
        $siswa = User::factory()->siswa()->create();

        $response = $this->actingAs($siswa)->get(route('admin.students.index'));

        $response->assertStatus(403);
    }
}
