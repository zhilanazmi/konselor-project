<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSchoolSettingRequest extends FormRequest
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
            'principal_name' => ['required', 'string', 'max:255'],
            'principal_nip' => ['required', 'string', 'max:50'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'principal_name.required' => 'Nama kepala sekolah wajib diisi.',
            'principal_name.max' => 'Nama kepala sekolah maksimal 255 karakter.',
            'principal_nip.required' => 'NIP kepala sekolah wajib diisi.',
            'principal_nip.max' => 'NIP kepala sekolah maksimal 50 karakter.',
        ];
    }
}
