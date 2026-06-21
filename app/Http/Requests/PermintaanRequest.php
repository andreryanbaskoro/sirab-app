<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PermintaanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'tukang_id' => 'required|exists:users,id',
            'tipe_rumah_id' => 'required|exists:tipe_rumahs,id',
            'lokasi_proyek' => 'required|string|max:500',
            'luas_bangunan' => 'required|numeric|min:1',
            'catatan' => 'nullable|string',
        ];
    }

    public function messages(): array
    {
        return [
            'tukang_id.required' => 'Kepala tukang wajib dipilih.',
            'tukang_id.exists' => 'Kepala tukang tidak ditemukan.',
            'tipe_rumah_id.required' => 'Tipe rumah wajib dipilih.',
            'tipe_rumah_id.exists' => 'Tipe rumah tidak ditemukan.',
            'lokasi_proyek.required' => 'Lokasi proyek wajib diisi.',
            'luas_bangunan.required' => 'Luas bangunan wajib diisi.',
            'luas_bangunan.numeric' => 'Luas bangunan harus berupa angka.',
            'luas_bangunan.min' => 'Luas bangunan minimal 1.',
        ];
    }
}
