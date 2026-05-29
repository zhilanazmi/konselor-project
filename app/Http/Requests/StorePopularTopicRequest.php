<?php

namespace App\Http\Requests;

use App\Enums\UserRole;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StorePopularTopicRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user() && $this->user()->role === UserRole::GuruBk;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:500'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:5120'],
            'icon' => ['nullable', 'string', 'max:100'],
            'icon_color' => ['nullable', 'string', 'max:7'],
            'order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'title.required' => 'Judul topik wajib diisi.',
            'title.max' => 'Judul topik maksimal 255 karakter.',
            'description.required' => 'Deskripsi wajib diisi.',
            'description.max' => 'Deskripsi maksimal 500 karakter.',
            'image.image' => 'File harus berupa gambar.',
            'image.mimes' => 'Gambar harus berformat JPG, JPEG, atau PNG.',
            'image.max' => 'Ukuran gambar maksimal 5MB.',
            'order.integer' => 'Urutan harus berupa angka.',
            'order.min' => 'Urutan minimal 0.',
        ];
    }
}
