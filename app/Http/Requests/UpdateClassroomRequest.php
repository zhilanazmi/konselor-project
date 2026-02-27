<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateClassroomRequest extends FormRequest
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
            'homeroom_teacher_id' => ['required', 'exists:teachers,id'],
            'name' => ['required', 'string', 'max:20'],
            'grade' => ['required', 'in:7,8,9'],
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
            'homeroom_teacher_id.required' => 'Wali kelas wajib dipilih.',
            'homeroom_teacher_id.exists' => 'Wali kelas tidak valid.',
            'name.required' => 'Nama kelas wajib diisi.',
            'grade.required' => 'Tingkat wajib dipilih.',
            'grade.in' => 'Tingkat harus 7, 8, atau 9.',
        ];
    }
}
