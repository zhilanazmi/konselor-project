<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateIndividualCounselingRequest extends FormRequest
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
            'student_id' => ['required', 'exists:students,id'],
            'scheduled_at' => ['required', 'date'],
            'status' => ['required', 'in:scheduled,ongoing,completed,followed_up'],
            'category' => ['required', 'in:pribadi,sosial,belajar,karir'],
            'problem_description' => ['required', 'string'],
            'approach' => ['nullable', 'string'],
            'result' => ['nullable', 'string'],
            'follow_up_plan' => ['nullable', 'string'],
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
            'student_id.required' => 'Siswa wajib dipilih.',
            'student_id.exists' => 'Siswa tidak ditemukan.',
            'scheduled_at.required' => 'Tanggal & waktu sesi wajib diisi.',
            'scheduled_at.date' => 'Tanggal & waktu sesi harus berupa tanggal yang valid.',
            'status.required' => 'Status wajib dipilih.',
            'status.in' => 'Status tidak valid.',
            'category.required' => 'Kategori wajib dipilih.',
            'category.in' => 'Kategori tidak valid.',
            'problem_description.required' => 'Deskripsi masalah wajib diisi.',
        ];
    }
}
