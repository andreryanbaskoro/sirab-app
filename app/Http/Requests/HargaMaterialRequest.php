<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class HargaMaterialRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'material_id' => 'required|exists:materials,id',
            'harga' => 'required|numeric|min:0',
            'tanggal_berlaku' => 'required|date',
            'keterangan' => 'nullable|string',
        ];
    }

    public function messages(): array
    {
        return [
            'material_id.required' => 'Material wajib dipilih.',
            'material_id.exists' => 'Material tidak ditemukan.',
            'harga.required' => 'Harga wajib diisi.',
            'harga.numeric' => 'Harga harus berupa angka.',
            'harga.min' => 'Harga tidak boleh negatif.',
            'tanggal_berlaku.required' => 'Tanggal berlaku wajib diisi.',
            'tanggal_berlaku.date' => 'Format tanggal tidak valid.',
        ];
    }
}
