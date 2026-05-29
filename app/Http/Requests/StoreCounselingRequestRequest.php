<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class StoreCounselingRequestRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        if (auth()->user()?->isSiswa()) {
            $this->merge([
                'student_id' => auth()->user()->student?->id,
            ]);
        }
    }

    /**
     * @return array<string, array<int, mixed>>
     */
    public function rules(): array
    {
        return [
            'student_id' => [
                'required',
                'exists:students,id',
                function (string $attribute, mixed $value, callable $fail): void {
                    $user = auth()->user();
                    if ($user->isOrangTua()) {
                        $guardian = $user->guardian;
                        if (! $guardian || ! $guardian->students()->where('students.id', $value)->exists()) {
                            $fail('Siswa yang dipilih bukan merupakan anak/wali Anda.');
                        }
                    }
                },
            ],
            'counselor_id' => [
                'required',
                'exists:users,id',
                function (string $attribute, mixed $value, callable $fail): void {
                    $counselor = User::query()->find($value);
                    if (! $counselor || ! $counselor->isGuruBk()) {
                        $fail('Guru BK yang dipilih tidak valid.');
                    }
                },
            ],
            'reason' => ['required', 'string', 'min:10'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'student_id.required' => 'Siswa wajib dipilih.',
            'student_id.exists' => 'Siswa tidak ditemukan.',
            'counselor_id.required' => 'Guru BK wajib dipilih.',
            'counselor_id.exists' => 'Guru BK tidak ditemukan.',
            'reason.required' => 'Alasan pengajuan wajib diisi.',
            'reason.string' => 'Alasan pengajuan harus berupa teks.',
            'reason.min' => 'Alasan pengajuan minimal 10 karakter.',
        ];
    }
}
