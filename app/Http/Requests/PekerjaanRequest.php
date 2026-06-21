<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PekerjaanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nama_pekerjaan' => 'required|string|max:255',
            'satuan' => 'required|string|max:50',
            'deskripsi' => 'nullable|string',
        ];
    }

    public function messages(): array
    {
        return [
            'nama_pekerjaan.required' => 'Nama pekerjaan wajib diisi.',
            'nama_pekerjaan.max' => 'Nama pekerjaan maksimal 255 karakter.',
            'satuan.required' => 'Satuan wajib diisi.',
        ];
    }
}
