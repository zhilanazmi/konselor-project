<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreGroupCounselingRequest extends FormRequest
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
            'topic' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'method' => ['nullable', 'string'],
            'scheduled_at' => ['required', 'date'],
            'status' => ['required', 'in:scheduled,ongoing,completed'],
            'result' => ['nullable', 'string'],
            'evaluation' => ['nullable', 'string'],
            'student_ids' => ['nullable', 'array'],
            'student_ids.*' => ['exists:students,id'],
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
            'topic.required' => 'Topik wajib diisi.',
            'topic.max' => 'Topik maksimal 255 karakter.',
            'scheduled_at.required' => 'Tanggal & waktu sesi wajib diisi.',
            'scheduled_at.date' => 'Tanggal & waktu sesi tidak valid.',
            'status.required' => 'Status wajib dipilih.',
            'status.in' => 'Status tidak valid.',
            'student_ids.*.exists' => 'Salah satu siswa yang dipilih tidak ditemukan.',
        ];
    }
}
