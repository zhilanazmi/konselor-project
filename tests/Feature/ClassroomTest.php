<?php

namespace Tests\Feature;

use App\Models\AcademicYear;
use App\Models\Classroom;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ClassroomTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_view_classrooms_index(): void
    {
        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin)->get(route('admin.classrooms.index'));

        $response->assertStatus(200);
        $response->assertViewIs('classrooms.index');
    }

    public function test_admin_can_create_classroom(): void
    {
        $admin = User::factory()->admin()->create();
        $year = AcademicYear::factory()->create();
        $teacher = Teacher::factory()->create();

        $response = $this->actingAs($admin)->post(route('admin.classrooms.store'), [
            'academic_year_id' => $year->id,
            'homeroom_teacher_id' => $teacher->id,
            'name' => 'VII-A',
            'grade' => '7',
        ]);

        $response->assertRedirect(route('admin.classrooms.index'));
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('classrooms', ['name' => 'VII-A', 'grade' => '7']);
    }

    public function test_admin_can_update_classroom(): void
    {
        $admin = User::factory()->admin()->create();
        $classroom = Classroom::factory()->create(['name' => 'VII-A']);
        $newTeacher = Teacher::factory()->create();

        $response = $this->actingAs($admin)->put(route('admin.classrooms.update', $classroom), [
            'academic_year_id' => $classroom->academic_year_id,
            'homeroom_teacher_id' => $newTeacher->id,
            'name' => 'VII-B',
            'grade' => '7',
        ]);

        $response->assertRedirect(route('admin.classrooms.index'));
        $this->assertDatabaseHas('classrooms', ['id' => $classroom->id, 'name' => 'VII-B']);
    }

    public function test_admin_can_delete_empty_classroom(): void
    {
        $admin = User::factory()->admin()->create();
        $classroom = Classroom::factory()->create();

        $response = $this->actingAs($admin)->delete(route('admin.classrooms.destroy', $classroom));

        $response->assertRedirect(route('admin.classrooms.index'));
        $response->assertSessionHas('success');
        $this->assertDatabaseMissing('classrooms', ['id' => $classroom->id]);
    }

    public function test_admin_cannot_delete_classroom_with_students(): void
    {
        $admin = User::factory()->admin()->create();
        $classroom = Classroom::factory()->create();
        $student = Student::factory()->create();
        $classroom->students()->attach($student);

        $response = $this->actingAs($admin)->delete(route('admin.classrooms.destroy', $classroom));

        $response->assertRedirect(route('admin.classrooms.index'));
        $response->assertSessionHas('error');
        $this->assertDatabaseHas('classrooms', ['id' => $classroom->id]);
    }

    public function test_admin_can_view_classroom_detail(): void
    {
        $admin = User::factory()->admin()->create();
        $classroom = Classroom::factory()->create();

        $response = $this->actingAs($admin)->get(route('admin.classrooms.show', $classroom));

        $response->assertStatus(200);
        $response->assertViewIs('classrooms.show');
    }

    public function test_admin_can_add_students_to_classroom(): void
    {
        $admin = User::factory()->admin()->create();
        $classroom = Classroom::factory()->create();
        $students = Student::factory()->count(2)->create();

        $response = $this->actingAs($admin)->post(route('admin.classrooms.add-students', $classroom), [
            'student_ids' => $students->pluck('id')->toArray(),
        ]);

        $response->assertRedirect(route('admin.classrooms.show', $classroom));
        $response->assertSessionHas('success');
        $this->assertCount(2, $classroom->fresh()->students);
    }

    public function test_admin_can_remove_student_from_classroom(): void
    {
        $admin = User::factory()->admin()->create();
        $classroom = Classroom::factory()->create();
        $student = Student::factory()->create();
        $classroom->students()->attach($student);

        $response = $this->actingAs($admin)->delete(route('admin.classrooms.remove-student', [$classroom, $student]));

        $response->assertRedirect(route('admin.classrooms.show', $classroom));
        $response->assertSessionHas('success');
        $this->assertCount(0, $classroom->fresh()->students);
    }

    public function test_validation_errors_for_classroom(): void
    {
        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin)->post(route('admin.classrooms.store'), []);

        $response->assertSessionHasErrors(['academic_year_id', 'homeroom_teacher_id', 'name', 'grade']);
    }

    public function test_non_admin_cannot_access_classrooms(): void
    {
        $guru = User::factory()->guru()->create();

        $response = $this->actingAs($guru)->get(route('admin.classrooms.index'));

        $response->assertStatus(403);
    }
}
