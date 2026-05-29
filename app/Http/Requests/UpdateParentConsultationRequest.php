<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateParentConsultationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, array<int, string>>
     */
    public function rules(): array
    {
        return [
            'academic_year_id' => ['required', 'exists:academic_years,id'],
            'guardian_id' => ['required', 'exists:guardians,id'],
            'student_id' => ['required', 'exists:students,id'],
            'scheduled_at' => ['required', 'date'],
            'status' => ['required', 'in:requested,scheduled,completed'],
            'requested_by' => ['required', 'in:guru_bk,orang_tua'],
            'topic' => ['required', 'string'],
            'notes' => ['nullable', 'string'],
            'result' => ['nullable', 'string'],
            'evaluation' => ['nullable', 'string'],
            'follow_up' => ['nullable', 'string'],
            'agreement' => ['nullable', 'string'],
            'documents' => ['nullable', 'array'],
            'documents.*' => ['file', 'mimes:jpg,jpeg,png', 'max:5120'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'academic_year_id.required' => 'Tahun ajaran wajib dipilih.',
            'academic_year_id.exists' => 'Tahun ajaran tidak valid.',
            'guardian_id.required' => 'Orang tua/wali wajib dipilih.',
            'guardian_id.exists' => 'Data orang tua/wali tidak valid.',
            'student_id.required' => 'Siswa wajib dipilih.',
            'student_id.exists' => 'Data siswa tidak valid.',
            'scheduled_at.required' => 'Tanggal & waktu jadwal wajib diisi.',
            'scheduled_at.date' => 'Format tanggal & waktu tidak valid.',
            'status.required' => 'Status wajib dipilih.',
            'status.in' => 'Status tidak valid.',
            'requested_by.required' => 'Pemohon wajib dipilih.',
            'requested_by.in' => 'Pemohon tidak valid.',
            'topic.required' => 'Topik konsultasi wajib diisi.',
            'documents.*.mimes' => 'Format file tidak didukung. Gunakan JPG atau PNG.',
            'documents.*.max' => 'Ukuran file maksimal 5 MB.',
        ];
    }
}
