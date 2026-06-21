<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TipeRumah;
use App\Models\Material;
use App\Models\HargaMaterial;
use App\Models\Pekerjaan;
use App\Models\HargaPekerjaan;
use App\Models\User;
use App\Models\HargaJasaTukang;

class MasterDataSeeder extends Seeder
{
    public function run(): void
    {
        // Tipe Rumah
        $tipe36 = TipeRumah::create(['nama_tipe' => 'Tipe 36', 'luas' => 36.00, 'deskripsi' => 'Rumah Sederhana Tipe 36']);
        $tipe45 = TipeRumah::create(['nama_tipe' => 'Tipe 45', 'luas' => 45.00, 'deskripsi' => 'Rumah Menengah Tipe 45']);

        // Material
        $semen = Material::create(['nama_material' => 'Semen Portland', 'satuan' => 'Sak (50kg)']);
        $pasir = Material::create(['nama_material' => 'Pasir Pasang', 'satuan' => 'M3']);
        $batu = Material::create(['nama_material' => 'Batu Kali', 'satuan' => 'M3']);
        $bata = Material::create(['nama_material' => 'Bata Merah', 'satuan' => 'Buah']);

        // Harga Material
        HargaMaterial::create(['material_id' => $semen->id, 'harga' => 60000, 'tanggal_berlaku' => now()]);
        HargaMaterial::create(['material_id' => $pasir->id, 'harga' => 250000, 'tanggal_berlaku' => now()]);
        HargaMaterial::create(['material_id' => $batu->id, 'harga' => 200000, 'tanggal_berlaku' => now()]);
        HargaMaterial::create(['material_id' => $bata->id, 'harga' => 1000, 'tanggal_berlaku' => now()]);

        // Pekerjaan
        $galian = Pekerjaan::create(['nama_pekerjaan' => 'Pekerjaan Galian Tanah', 'satuan' => 'M3']);
        $pondasi = Pekerjaan::create(['nama_pekerjaan' => 'Pekerjaan Pondasi Batu Kali', 'satuan' => 'M3']);
        $dinding = Pekerjaan::create(['nama_pekerjaan' => 'Pekerjaan Pasangan Bata', 'satuan' => 'M2']);

        // Harga Pekerjaan (Harga Upah standar)
        HargaPekerjaan::create(['pekerjaan_id' => $galian->id, 'harga' => 75000, 'tanggal_berlaku' => now()]);
        HargaPekerjaan::create(['pekerjaan_id' => $pondasi->id, 'harga' => 150000, 'tanggal_berlaku' => now()]);
        HargaPekerjaan::create(['pekerjaan_id' => $dinding->id, 'harga' => 60000, 'tanggal_berlaku' => now()]);

        // Harga Jasa Tukang (Opsional, diinput oleh Tukang sendiri, tapi kita sediakan dummy)
        $tukang1 = User::role('kepala_tukang')->first();
        if ($tukang1) {
            HargaJasaTukang::create([
                'user_id' => $tukang1->id,
                'nama_jasa' => 'Harian',
                'harga' => 150000,
                'deskripsi' => 'Harga jasa harian Kepala Tukang Budi'
            ]);
            HargaJasaTukang::create([
                'user_id' => $tukang1->id,
                'nama_jasa' => 'Borongan',
                'harga' => 3000000,
                'deskripsi' => 'Harga jasa borongan per m2 (estimasi)'
            ]);
        }
    }
}
