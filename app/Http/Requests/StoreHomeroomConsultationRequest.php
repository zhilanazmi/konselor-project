<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreHomeroomConsultationRequest extends FormRequest
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
            'teacher_id' => ['required', 'exists:teachers,id'],
            'student_id' => ['required', 'exists:students,id'],
            'consultation_date' => ['required', 'date'],
            'topic' => ['required', 'string'],
            'recommendation' => ['nullable', 'string'],
            'follow_up' => ['nullable', 'string'],
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
            'teacher_id.required' => 'Wali kelas wajib dipilih.',
            'teacher_id.exists' => 'Wali kelas tidak ditemukan.',
            'student_id.required' => 'Siswa wajib dipilih.',
            'student_id.exists' => 'Siswa tidak ditemukan.',
            'consultation_date.required' => 'Tanggal konsultasi wajib diisi.',
            'consultation_date.date' => 'Tanggal konsultasi tidak valid.',
            'topic.required' => 'Topik wajib diisi.',
        ];
    }
}
