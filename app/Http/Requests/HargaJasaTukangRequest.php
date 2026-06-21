<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class HargaJasaTukangRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nama_jasa' => 'required|string|max:255',
            'harga' => 'required|numeric|min:0',
            'deskripsi' => 'nullable|string',
        ];
    }

    public function messages(): array
    {
        return [
            'nama_jasa.required' => 'Nama jasa wajib diisi.',
            'nama_jasa.max' => 'Nama jasa maksimal 255 karakter.',
            'harga.required' => 'Harga wajib diisi.',
            'harga.numeric' => 'Harga harus berupa angka.',
            'harga.min' => 'Harga tidak boleh negatif.',
        ];
    }
}
