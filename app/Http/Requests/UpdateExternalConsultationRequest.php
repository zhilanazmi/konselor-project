<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateExternalConsultationRequest extends FormRequest
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
            'student_id' => ['nullable', 'exists:students,id'],
            'consultation_date' => ['required', 'date'],
            'external_party_name' => ['required', 'string', 'max:255'],
            'external_party_role' => ['required', 'string', 'max:255'],
            'topic' => ['required', 'string'],
            'notes' => ['nullable', 'string'],
            'evaluation' => ['nullable', 'string'],
            'follow_up' => ['nullable', 'string'],
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
            'student_id.exists' => 'Siswa tidak ditemukan.',
            'consultation_date.required' => 'Tanggal konsultasi wajib diisi.',
            'consultation_date.date' => 'Tanggal konsultasi tidak valid.',
            'external_party_name.required' => 'Nama pihak luar wajib diisi.',
            'external_party_name.max' => 'Nama pihak luar maksimal 255 karakter.',
            'external_party_role.required' => 'Peran/hubungan pihak luar wajib diisi.',
            'external_party_role.max' => 'Peran/hubungan maksimal 255 karakter.',
            'topic.required' => 'Topik konsultasi wajib diisi.',
            'documents.*.mimes' => 'Format file tidak didukung. Gunakan JPG atau PNG.',
            'documents.*.max' => 'Ukuran file maksimal 5 MB.',
        ];
    }
}
