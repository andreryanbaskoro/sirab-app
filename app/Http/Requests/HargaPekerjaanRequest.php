<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class HargaPekerjaanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'pekerjaan_id' => 'required|exists:pekerjaans,id',
            'harga' => 'required|numeric|min:0',
            'tanggal_berlaku' => 'required|date',
            'keterangan' => 'nullable|string',
        ];
    }

    public function messages(): array
    {
        return [
            'pekerjaan_id.required' => 'Pekerjaan wajib dipilih.',
            'pekerjaan_id.exists' => 'Pekerjaan tidak ditemukan.',
            'harga.required' => 'Harga wajib diisi.',
            'harga.numeric' => 'Harga harus berupa angka.',
            'harga.min' => 'Harga tidak boleh negatif.',
            'tanggal_berlaku.required' => 'Tanggal berlaku wajib diisi.',
            'tanggal_berlaku.date' => 'Format tanggal tidak valid.',
        ];
    }
}
