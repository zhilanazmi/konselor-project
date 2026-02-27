<?php

namespace Tests\Feature;

use App\Models\AcademicYear;
use App\Models\Classroom;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AcademicYearTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_view_academic_years_index(): void
    {
        $admin = User::factory()->admin()->create();
        AcademicYear::factory()->count(3)->create();

        $response = $this->actingAs($admin)->get(route('admin.academic-years.index'));

        $response->assertStatus(200);
        $response->assertViewIs('academic-years.index');
        $response->assertViewHas('academicYears');
    }

    public function test_admin_can_search_academic_years(): void
    {
        $admin = User::factory()->admin()->create();
        AcademicYear::factory()->create(['name' => '2025/2026']);
        AcademicYear::factory()->create(['name' => '2024/2025']);

        $response = $this->actingAs($admin)->get(route('admin.academic-years.index', ['search' => '2025/2026']));

        $response->assertStatus(200);
        $response->assertSee('2025/2026');
    }

    public function test_admin_can_view_create_form(): void
    {
        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin)->get(route('admin.academic-years.create'));

        $response->assertStatus(200);
        $response->assertViewIs('academic-years.create');
    }

    public function test_admin_can_create_academic_year(): void
    {
        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin)->post(route('admin.academic-years.store'), [
            'name' => '2025/2026',
            'start_date' => '2025-07-01',
            'end_date' => '2026-06-30',
            'is_active' => '1',
        ]);

        $response->assertRedirect(route('admin.academic-years.index'));
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('academic_years', [
            'name' => '2025/2026',
            'is_active' => true,
        ]);
    }

    public function test_admin_can_update_academic_year(): void
    {
        $admin = User::factory()->admin()->create();
        $year = AcademicYear::factory()->create(['name' => '2025/2026']);

        $response = $this->actingAs($admin)->put(route('admin.academic-years.update', $year), [
            'name' => '2025/2026 Ganjil',
            'start_date' => '2025-07-01',
            'end_date' => '2026-06-30',
            'is_active' => '0',
        ]);

        $response->assertRedirect(route('admin.academic-years.index'));
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('academic_years', [
            'id' => $year->id,
            'name' => '2025/2026 Ganjil',
        ]);
    }

    public function test_admin_can_delete_academic_year_without_classrooms(): void
    {
        $admin = User::factory()->admin()->create();
        $year = AcademicYear::factory()->create();

        $response = $this->actingAs($admin)->delete(route('admin.academic-years.destroy', $year));

        $response->assertRedirect(route('admin.academic-years.index'));
        $response->assertSessionHas('success');
        $this->assertDatabaseMissing('academic_years', ['id' => $year->id]);
    }

    public function test_admin_cannot_delete_academic_year_with_classrooms(): void
    {
        $admin = User::factory()->admin()->create();
        $year = AcademicYear::factory()->create();
        Classroom::factory()->create(['academic_year_id' => $year->id]);

        $response = $this->actingAs($admin)->delete(route('admin.academic-years.destroy', $year));

        $response->assertRedirect(route('admin.academic-years.index'));
        $response->assertSessionHas('error');
        $this->assertDatabaseHas('academic_years', ['id' => $year->id]);
    }

    public function test_setting_active_deactivates_others(): void
    {
        $admin = User::factory()->admin()->create();
        $existingYear = AcademicYear::factory()->active()->create(['name' => '2024/2025']);

        $this->actingAs($admin)->post(route('admin.academic-years.store'), [
            'name' => '2025/2026',
            'start_date' => '2025-07-01',
            'end_date' => '2026-06-30',
            'is_active' => '1',
        ]);

        $this->assertDatabaseHas('academic_years', [
            'id' => $existingYear->id,
            'is_active' => false,
        ]);
        $this->assertDatabaseHas('academic_years', [
            'name' => '2025/2026',
            'is_active' => true,
        ]);
    }

    public function test_non_admin_cannot_access_academic_years(): void
    {
        $siswa = User::factory()->siswa()->create();

        $response = $this->actingAs($siswa)->get(route('admin.academic-years.index'));

        $response->assertStatus(403);
    }

    public function test_validation_errors_for_invalid_data(): void
    {
        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin)->post(route('admin.academic-years.store'), [
            'name' => '',
            'start_date' => '',
            'end_date' => '',
        ]);

        $response->assertSessionHasErrors(['name', 'start_date', 'end_date']);
    }

    public function test_validation_end_date_must_be_after_start_date(): void
    {
        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin)->post(route('admin.academic-years.store'), [
            'name' => '2025/2026',
            'start_date' => '2026-06-30',
            'end_date' => '2025-07-01',
            'is_active' => '0',
        ]);

        $response->assertSessionHasErrors(['end_date']);
    }

    public function test_validation_unique_name(): void
    {
        $admin = User::factory()->admin()->create();
        AcademicYear::factory()->create(['name' => '2025/2026']);

        $response = $this->actingAs($admin)->post(route('admin.academic-years.store'), [
            'name' => '2025/2026',
            'start_date' => '2025-07-01',
            'end_date' => '2026-06-30',
            'is_active' => '0',
        ]);

        $response->assertSessionHasErrors(['name']);
    }

    public function test_guest_is_redirected_to_login(): void
    {
        $response = $this->get(route('admin.academic-years.index'));

        $response->assertRedirect(route('login'));
    }
}
