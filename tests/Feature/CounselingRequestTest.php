<?php

namespace Tests\Feature;

use App\Models\AcademicYear;
use App\Models\CounselingRequest;
use App\Models\Guardian;
use App\Models\Student;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CounselingRequestTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_access_counseling_requests(): void
    {
        $response = $this->get(route('siswa.counseling-requests.index'));
        $response->assertRedirect(route('login'));
    }

    public function test_siswa_can_view_index(): void
    {
        $siswaUser = User::factory()->siswa()->create();
        $student = Student::factory()->create(['user_id' => $siswaUser->id]);
        $counselor = User::factory()->guruBk()->create();

        CounselingRequest::factory()->count(2)->create([
            'student_id' => $student->id,
            'counselor_id' => $counselor->id,
        ]);

        $response = $this->actingAs($siswaUser)->get(route('siswa.counseling-requests.index'));

        $response->assertStatus(200);
        $response->assertViewIs('counseling-requests.index');
    }

    public function test_siswa_can_view_create_form(): void
    {
        $siswaUser = User::factory()->siswa()->create();
        $student = Student::factory()->create(['user_id' => $siswaUser->id]);

        $response = $this->actingAs($siswaUser)->get(route('siswa.counseling-requests.create'));

        $response->assertStatus(200);
        $response->assertViewIs('counseling-requests.create');
    }

    public function test_siswa_can_store_counseling_request(): void
    {
        $siswaUser = User::factory()->siswa()->create();
        $student = Student::factory()->create(['user_id' => $siswaUser->id]);
        $counselor = User::factory()->guruBk()->create();

        $response = $this->actingAs($siswaUser)->post(route('siswa.counseling-requests.store'), [
            'counselor_id' => $counselor->id,
            'reason' => 'Saya butuh bantuan bimbingan karir untuk mendaftar kuliah.',
        ]);

        $response->assertRedirect(route('siswa.counseling-requests.index'));
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('counseling_requests', [
            'student_id' => $student->id,
            'counselor_id' => $counselor->id,
            'status' => 'pending',
            'reason' => 'Saya butuh bantuan bimbingan karir untuk mendaftar kuliah.',
        ]);
    }

    public function test_orang_tua_can_store_counseling_request_for_child(): void
    {
        $otUser = User::factory()->orangTua()->create();
        $guardian = Guardian::factory()->create(['user_id' => $otUser->id]);
        $student = Student::factory()->create();

        // Link child to guardian
        $guardian->students()->attach($student->id, ['relationship' => 'ayah']);

        $counselor = User::factory()->guruBk()->create();

        $response = $this->actingAs($otUser)->post(route('orang-tua.counseling-requests.store'), [
            'student_id' => $student->id,
            'counselor_id' => $counselor->id,
            'reason' => 'Saya ingin anak saya dikonseling karena belakangan nilainya menurun.',
        ]);

        $response->assertRedirect(route('orang-tua.counseling-requests.index'));
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('counseling_requests', [
            'student_id' => $student->id,
            'counselor_id' => $counselor->id,
            'status' => 'pending',
            'reason' => 'Saya ingin anak saya dikonseling karena belakangan nilainya menurun.',
        ]);
    }

    public function test_orang_tua_cannot_store_counseling_request_for_other_student(): void
    {
        $otUser = User::factory()->orangTua()->create();
        $guardian = Guardian::factory()->create(['user_id' => $otUser->id]);
        $student = Student::factory()->create(); // Not attached/linked to guardian

        $counselor = User::factory()->guruBk()->create();

        $response = $this->actingAs($otUser)->post(route('orang-tua.counseling-requests.store'), [
            'student_id' => $student->id,
            'counselor_id' => $counselor->id,
            'reason' => 'Permohonan ilegal.',
        ]);

        $response->assertSessionHasErrors(['student_id']);
    }

    public function test_guru_bk_can_view_index_and_show(): void
    {
        $guruBk = User::factory()->guruBk()->create();
        $student = Student::factory()->create();
        $request = CounselingRequest::factory()->create(['student_id' => $student->id]);

        $response = $this->actingAs($guruBk)->get(route('guru-bk.counseling-requests.index'));
        $response->assertStatus(200);

        $response = $this->actingAs($guruBk)->get(route('guru-bk.counseling-requests.show', $request));
        $response->assertStatus(200);
        $response->assertViewIs('counseling-requests.show');
    }

    public function test_guru_bk_can_approve_request(): void
    {
        $guruBk = User::factory()->guruBk()->create();
        $student = Student::factory()->create();
        $counselingRequest = CounselingRequest::factory()->create([
            'student_id' => $student->id,
            'counselor_id' => $guruBk->id,
            'status' => 'pending',
            'reason' => 'Kendala belajar.',
        ]);

        $academicYear = AcademicYear::factory()->active()->create();

        $response = $this->actingAs($guruBk)->post(route('guru-bk.counseling-requests.approve', $counselingRequest), [
            'scheduled_at' => '2026-06-01 10:00:00',
            'category' => 'belajar',
            'admin_notes' => 'Telah disetujui, silakan hadir.',
        ]);

        $response->assertRedirect(route('guru-bk.counseling-requests.show', $counselingRequest));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('counseling_requests', [
            'id' => $counselingRequest->id,
            'status' => 'approved',
            'admin_notes' => 'Telah disetujui, silakan hadir.',
        ]);

        $this->assertDatabaseHas('individual_counselings', [
            'academic_year_id' => $academicYear->id,
            'counselor_id' => $guruBk->id,
            'student_id' => $student->id,
            'scheduled_at' => '2026-06-01 10:00:00',
            'category' => 'belajar',
            'status' => 'scheduled',
            'problem_description' => 'Kendala belajar.',
        ]);
    }

    public function test_guru_bk_can_reject_request(): void
    {
        $guruBk = User::factory()->guruBk()->create();
        $student = Student::factory()->create();
        $counselingRequest = CounselingRequest::factory()->create([
            'student_id' => $student->id,
            'status' => 'pending',
        ]);

        $response = $this->actingAs($guruBk)->post(route('guru-bk.counseling-requests.reject', $counselingRequest), [
            'admin_notes' => 'Jadwal saya penuh pada minggu ini.',
        ]);

        $response->assertRedirect(route('guru-bk.counseling-requests.show', $counselingRequest));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('counseling_requests', [
            'id' => $counselingRequest->id,
            'status' => 'rejected',
            'admin_notes' => 'Jadwal saya penuh pada minggu ini.',
        ]);
    }

    public function test_non_authorized_role_cannot_approve_or_reject_requests(): void
    {
        $siswaUser = User::factory()->siswa()->create();
        $student = Student::factory()->create(['user_id' => $siswaUser->id]);
        $counselingRequest = CounselingRequest::factory()->create([
            'student_id' => $student->id,
            'status' => 'pending',
        ]);

        $response = $this->actingAs($siswaUser)->post(route('guru-bk.counseling-requests.approve', $counselingRequest), [
            'scheduled_at' => '2026-06-01 10:00:00',
            'category' => 'pribadi',
        ]);
        $response->assertStatus(403);

        $response = $this->actingAs($siswaUser)->post(route('guru-bk.counseling-requests.reject', $counselingRequest), [
            'admin_notes' => 'Catatan ilegal.',
        ]);
        $response->assertStatus(403);
    }
}
