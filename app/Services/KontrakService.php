<?php

namespace App\Services;

use App\Models\Kontrak;
use App\Models\Permintaan;
use App\Models\Rab;
use App\Enums\KontrakStatus;
use App\Enums\PermintaanStatus;
use Illuminate\Support\Facades\DB;

class KontrakService
{
    public function createContract(Rab $rab): Kontrak
    {
        return DB::transaction(function () use ($rab) {
            $permintaan = $rab->permintaan;

            $kontrak = Kontrak::create([
                'nomor_kontrak' => $this->generateNomorKontrak(),
                'permintaan_id' => $permintaan->id,
                'rab_id' => $rab->id,
                'konsumen_id' => $permintaan->konsumen_id,
                'tukang_id' => $permintaan->tukang_id,
                'nilai_kontrak' => $rab->total_final,
                'status' => KontrakStatus::DRAFT,
            ]);

            return $kontrak;
        });
    }

    public function generateNomorKontrak(): string
    {
        $prefix = 'SPK-' . now()->format('Ym') . '-';
        $last = Kontrak::withTrashed()->where('nomor_kontrak', 'like', $prefix . '%')
            ->orderBy('id', 'desc')->first();
        $seq = $last ? ((int) substr($last->nomor_kontrak, -5)) + 1 : 1;
        return $prefix . str_pad($seq, 5, '0', STR_PAD_LEFT);
    }

    public function activateContract(Kontrak $kontrak, array $data = []): Kontrak
    {
        return DB::transaction(function () use ($kontrak, $data) {
            $kontrak->update([
                'status' => KontrakStatus::AKTIF,
                'tanggal_mulai' => $data['tanggal_mulai'] ?? now()->toDateString(),
                'tanggal_selesai' => $data['tanggal_selesai'] ?? now()->addMonths(3)->toDateString(),
                'keterangan' => $data['keterangan'] ?? null,
            ]);

            $kontrak->permintaan->update([
                'status' => PermintaanStatus::KONTRAK_AKTIF,
            ]);

            return $kontrak->fresh();
        });
    }

    public function completeContract(Kontrak $kontrak): Kontrak
    {
        return DB::transaction(function () use ($kontrak) {
            $kontrak->update(['status' => KontrakStatus::SELESAI]);
            $kontrak->permintaan->update(['status' => PermintaanStatus::SELESAI]);
            return $kontrak->fresh();
        });
    }

    public function cancelContract(Kontrak $kontrak, string $alasan = ''): Kontrak
    {
        return DB::transaction(function () use ($kontrak, $alasan) {
            $kontrak->update([
                'status' => KontrakStatus::BATAL,
                'keterangan' => $alasan,
            ]);
            return $kontrak->fresh();
        });
    }
}
