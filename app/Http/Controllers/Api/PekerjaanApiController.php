<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pekerjaan;
use Illuminate\Http\Request;

class PekerjaanApiController extends Controller
{
    public function getMaterials($id)
    {
        $pekerjaan = Pekerjaan::with('materials.material')->find($id);

        if (!$pekerjaan) {
            return response()->json(['error' => 'Pekerjaan tidak ditemukan'], 404);
        }

        $materials = $pekerjaan->materials->map(function ($pm) {
            $hargaTerbaru = $pm->material->hargaTerbaru();
            return [
                'id' => $pm->material_id,
                'nama_material' => $pm->material->nama_material,
                'satuan' => $pm->material->satuan,
                'qty_dasar' => $pm->qty,
                'harga_satuan' => $hargaTerbaru ? $hargaTerbaru->harga : 0,
            ];
        });

        return response()->json([
            'pekerjaan_id' => $pekerjaan->id,
            'nama_pekerjaan' => $pekerjaan->nama_pekerjaan,
            'materials' => $materials
        ]);
    }
}
