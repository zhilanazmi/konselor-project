<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateGuruBkJournalRequest extends FormRequest
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
            'date' => ['required', 'date'],
            'activity_type' => ['required', 'in:layanan_dasar,layanan_responsif,layanan_perencanaan,dukungan_sistem'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'target_group' => ['nullable', 'string', 'max:255'],
            'location' => ['nullable', 'string', 'max:255'],
            'duration_minutes' => ['nullable', 'integer', 'min:1', 'max:9999'],
            'notes' => ['nullable', 'string'],
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
            'date.required' => 'Tanggal kegiatan wajib diisi.',
            'date.date' => 'Format tanggal tidak valid.',
            'activity_type.required' => 'Jenis kegiatan wajib dipilih.',
            'activity_type.in' => 'Jenis kegiatan tidak valid.',
            'title.required' => 'Judul kegiatan wajib diisi.',
            'title.max' => 'Judul kegiatan maksimal 255 karakter.',
            'duration_minutes.integer' => 'Durasi harus berupa angka.',
            'duration_minutes.min' => 'Durasi minimal 1 menit.',
        ];
    }
}
