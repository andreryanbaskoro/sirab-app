<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\TipeRumah;
use App\Models\Permintaan;
use App\Enums\PermintaanStatus;

class DummyTransactionSeeder extends Seeder
{
    public function run(): void
    {
        $konsumen = User::role('konsumen')->first();
        $tukang = User::role('kepala_tukang')->first();
        $tipe = TipeRumah::first();

        if ($konsumen && $tukang && $tipe) {
            // 1. Permintaan Baru (Pending)
            Permintaan::create([
                'konsumen_id' => $konsumen->id,
                'tukang_id' => $tukang->id,
                'tipe_rumah_id' => $tipe->id,
                'jenis_jasa' => 'bangun_baru',
                'lokasi_proyek' => 'Jl. Pembangunan No. 10 (Pending)',
                'luas_bangunan' => 36.00,
                'catatan' => 'Tolong buatkan RAB secepatnya ya pak.',
                'status' => PermintaanStatus::PENDING,
                'tanggal_permohonan' => now(),
            ]);

            // 2. Permintaan Menunggu Persetujuan Konsumen (Sudah ada RAB)
            $p2 = Permintaan::create([
                'konsumen_id' => $konsumen->id,
                'tukang_id' => $tukang->id,
                'tipe_rumah_id' => $tipe->id,
                'jenis_jasa' => 'renovasi',
                'lokasi_proyek' => 'Jl. Merdeka No. 45 (Menunggu Persetujuan)',
                'luas_bangunan' => 45.00,
                'status' => PermintaanStatus::MENUNGGU_PERSETUJUAN,
                'tanggal_permohonan' => now()->subDays(5),
            ]);

            $rab = \App\Models\Rab::create([
                'permintaan_id' => $p2->id,
                'tukang_id' => $tukang->id,
                'nomor_rab' => 'RAB-' . date('Ymd') . '-0001',
                'total_material' => 15000000,
                'total_upah' => 5000000,
                'biaya_jasa_tukang' => 2000000,
                'biaya_tambahan' => 0,
                'total_final' => 22000000,
                'status' => \App\Enums\RabStatus::MENUNGGU_PERSETUJUAN,
            ]);

            // 3. Kontrak Aktif (Proyek Berjalan)
            $p3 = Permintaan::create([
                'konsumen_id' => $konsumen->id,
                'tukang_id' => $tukang->id,
                'tipe_rumah_id' => $tipe->id,
                'jenis_jasa' => 'bangun_baru',
                'lokasi_proyek' => 'Komp. Harmoni Blok C/12 (Proyek Berjalan)',
                'luas_bangunan' => 60.00,
                'status' => PermintaanStatus::KONTRAK_AKTIF,
                'tanggal_permohonan' => now()->subDays(10),
            ]);

            $rab3 = \App\Models\Rab::create([
                'permintaan_id' => $p3->id,
                'tukang_id' => $tukang->id,
                'nomor_rab' => 'RAB-' . date('Ymd') . '-0002',
                'total_material' => 50000000,
                'total_upah' => 20000000,
                'biaya_jasa_tukang' => 5000000,
                'biaya_tambahan' => 1000000,
                'total_final' => 76000000,
                'status' => \App\Enums\RabStatus::DISETUJUI,
            ]);

            $kontrak = \App\Models\Kontrak::create([
                'nomor_kontrak' => 'SPK-' . date('Ymd') . '-0001',
                'permintaan_id' => $p3->id,
                'rab_id' => $rab3->id,
                'konsumen_id' => $konsumen->id,
                'tukang_id' => $tukang->id,
                'nilai_kontrak' => 76000000,
                'tanggal_mulai' => now()->subDays(2),
                'tanggal_selesai' => now()->addDays(30),
                'status' => \App\Enums\KontrakStatus::AKTIF,
            ]);

            // Tambah Pembayaran Termin 1
            $kontrak->pembayarans()->create([
                'termin' => 'DP 30%',
                'jumlah' => 22800000,
                'bukti_transfer' => null, // null for dummy
                'status' => 'diverifikasi'
            ]);
        }
    }
}
