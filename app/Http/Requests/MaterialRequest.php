<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MaterialRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nama_material' => 'required|string|max:255',
            'satuan' => 'required|string|max:50',
            'deskripsi' => 'nullable|string',
        ];
    }

    public function messages(): array
    {
        return [
            'nama_material.required' => 'Nama material wajib diisi.',
            'nama_material.max' => 'Nama material maksimal 255 karakter.',
            'satuan.required' => 'Satuan wajib diisi.',
        ];
    }
}
