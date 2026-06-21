<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RabRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'permintaan_id' => 'required|exists:permintaans,id',
            'jasa_tukang_id' => 'nullable|exists:harga_jasa_tukangs,id',
            'biaya_jasa_tukang' => 'nullable|numeric|min:0',
            'biaya_tambahan' => 'nullable|numeric|min:0',
            'keterangan_tambahan' => 'nullable|string|max:255',

            'materials' => 'nullable|array',
            'materials.*.material_id' => 'nullable|exists:materials,id',
            'materials.*.nama_item' => 'required_with:materials|string|max:255',
            'materials.*.qty' => 'required_with:materials|numeric|min:0.01',
            'materials.*.satuan' => 'required_with:materials|string|max:50',
            'materials.*.harga_satuan' => 'required_with:materials|numeric|min:0',

            'pekerjaans' => 'nullable|array',
            'pekerjaans.*.pekerjaan_id' => 'nullable|exists:pekerjaans,id',
            'pekerjaans.*.nama_item' => 'required_with:pekerjaans|string|max:255',
            'pekerjaans.*.qty' => 'required_with:pekerjaans|numeric|min:0.01',
            'pekerjaans.*.satuan' => 'required_with:pekerjaans|string|max:50',
            'pekerjaans.*.harga_satuan' => 'required_with:pekerjaans|numeric|min:0',
        ];
    }

    public function messages(): array
    {
        return [
            'permintaan_id.required' => 'Permintaan wajib dipilih.',
            'permintaan_id.exists' => 'Permintaan tidak ditemukan.',
            'materials.*.nama_item.required_with' => 'Nama item material wajib diisi.',
            'materials.*.qty.required_with' => 'Jumlah material wajib diisi.',
            'materials.*.harga_satuan.required_with' => 'Harga satuan material wajib diisi.',
            'pekerjaans.*.nama_item.required_with' => 'Nama item pekerjaan wajib diisi.',
            'pekerjaans.*.qty.required_with' => 'Jumlah pekerjaan wajib diisi.',
            'pekerjaans.*.harga_satuan.required_with' => 'Harga satuan pekerjaan wajib diisi.',
        ];
    }
}
