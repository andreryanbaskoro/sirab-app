<?php

namespace App\Services;

use App\Models\Rab;
use App\Models\RabDetail;
use App\Models\HargaMaterial;
use App\Models\HargaPekerjaan;
use App\Models\HargaJasaTukang;
use App\Enums\RabStatus;
use App\Enums\PermintaanStatus;
use Illuminate\Support\Facades\DB;

class RabCalculatorService
{
    public function createRab(array $data): Rab
    {
        return DB::transaction(function () use ($data) {
            $rab = Rab::create([
                'permintaan_id' => $data['permintaan_id'],
                'tukang_id' => $data['tukang_id'],
                'jasa_tukang_id' => $data['jasa_tukang_id'] ?? null,
                'biaya_jasa_tukang' => $data['biaya_jasa_tukang'] ?? 0,
                'biaya_tambahan' => $data['biaya_tambahan'] ?? 0,
                'total_material' => 0,
                'total_upah' => 0,
                'total_final' => 0,
                'status' => RabStatus::DRAFT->value,
            ]);

            // Add material items
            if (!empty($data['materials'])) {
                foreach ($data['materials'] as $item) {
                    $subtotal = $item['qty'] * $item['harga_satuan'];
                    RabDetail::create([
                        'rab_id' => $rab->id,
                        'jenis_item' => 'material',
                        'referensi_id' => $item['material_id'] ?? null,
                        'nama_item' => $item['nama_item'],
                        'qty' => $item['qty'],
                        'satuan' => $item['satuan'],
                        'harga_satuan' => $item['harga_satuan'],
                        'subtotal' => $subtotal,
                    ]);
                }
            }

            // Add pekerjaan items
            if (!empty($data['pekerjaans'])) {
                foreach ($data['pekerjaans'] as $item) {
                    $subtotal = $item['qty'] * $item['harga_satuan'];
                    RabDetail::create([
                        'rab_id' => $rab->id,
                        'jenis_item' => 'pekerjaan',
                        'referensi_id' => $item['pekerjaan_id'] ?? null,
                        'nama_item' => $item['nama_item'],
                        'qty' => $item['qty'],
                        'satuan' => $item['satuan'],
                        'harga_satuan' => $item['harga_satuan'],
                        'subtotal' => $subtotal,
                    ]);
                }
            }

            // Add jasa tukang as detail
            if (!empty($data['biaya_jasa_tukang']) && $data['biaya_jasa_tukang'] > 0) {
                RabDetail::create([
                    'rab_id' => $rab->id,
                    'jenis_item' => 'jasa_tukang',
                    'referensi_id' => $data['jasa_tukang_id'] ?? null,
                    'nama_item' => 'Jasa Kepala Tukang',
                    'qty' => 1,
                    'satuan' => 'ls',
                    'harga_satuan' => $data['biaya_jasa_tukang'],
                    'subtotal' => $data['biaya_jasa_tukang'],
                ]);
            }

            // Add biaya tambahan as detail if exists
            if (!empty($data['biaya_tambahan']) && $data['biaya_tambahan'] > 0) {
                RabDetail::create([
                    'rab_id' => $rab->id,
                    'jenis_item' => 'tambahan',
                    'referensi_id' => null,
                    'nama_item' => $data['keterangan_tambahan'] ?? 'Biaya Tambahan',
                    'qty' => 1,
                    'satuan' => 'ls',
                    'harga_satuan' => $data['biaya_tambahan'],
                    'subtotal' => $data['biaya_tambahan'],
                ]);
            }

            $this->recalculate($rab);

            return $rab->fresh(['details', 'permintaan', 'tukang']);
        });
    }

    public function recalculate(Rab $rab): Rab
    {
        $totalMaterial = $rab->details()->where('jenis_item', 'material')->sum('subtotal');
        $totalUpah = $rab->details()->where('jenis_item', 'pekerjaan')->sum('subtotal');
        $biayaJasa = $rab->details()->where('jenis_item', 'jasa_tukang')->sum('subtotal');
        $biayaTambahan = $rab->details()->where('jenis_item', 'tambahan')->sum('subtotal');

        $totalFinal = $totalMaterial + $totalUpah + $biayaJasa + $biayaTambahan;

        $rab->update([
            'total_material' => $totalMaterial,
            'total_upah' => $totalUpah,
            'biaya_jasa_tukang' => $biayaJasa,
            'biaya_tambahan' => $biayaTambahan,
            'total_final' => $totalFinal,
        ]);

        return $rab;
    }

    public function submitForApproval(Rab $rab): Rab
    {
        $rab->update(['status' => RabStatus::MENUNGGU_PERSETUJUAN->value]);
        $rab->permintaan->update(['status' => PermintaanStatus::MENUNGGU_PERSETUJUAN->value]);
        return $rab;
    }

    public function approve(Rab $rab): Rab
    {
        $rab->update(['status' => RabStatus::DISETUJUI->value]);
        $rab->permintaan->update(['status' => PermintaanStatus::DISETUJUI->value]);
        return $rab;
    }

    public function reject(Rab $rab): Rab
    {
        $rab->update(['status' => RabStatus::DITOLAK->value]);
        $rab->permintaan->update(['status' => PermintaanStatus::DITOLAK_KONSUMEN->value]);
        return $rab;
    }

    public function getLatestMaterialPrice(int $materialId): ?float
    {
        $harga = HargaMaterial::where('material_id', $materialId)
            ->orderBy('tanggal_berlaku', 'desc')
            ->first();
        return $harga ? (float) $harga->harga : null;
    }

    public function getLatestPekerjaanPrice(int $pekerjaanId): ?float
    {
        $harga = HargaPekerjaan::where('pekerjaan_id', $pekerjaanId)
            ->orderBy('tanggal_berlaku', 'desc')
            ->first();
        return $harga ? (float) $harga->harga : null;
    }
}
