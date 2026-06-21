<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TipeRumahRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nama_tipe' => 'required|string|max:255',
            'luas' => 'required|numeric|min:0',
            'deskripsi' => 'nullable|string',
        ];
    }

    public function messages(): array
    {
        return [
            'nama_tipe.required' => 'Nama tipe rumah wajib diisi.',
            'nama_tipe.max' => 'Nama tipe rumah maksimal 255 karakter.',
            'luas.required' => 'Luas wajib diisi.',
            'luas.numeric' => 'Luas harus berupa angka.',
            'luas.min' => 'Luas tidak boleh negatif.',
        ];
    }
}
