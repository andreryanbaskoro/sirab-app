<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\KategoriPekerjaan;
use App\Models\Pekerjaan;

class KategoriPekerjaanSeeder extends Seeder
{
    public function run(): void
    {
        $kategori1 = KategoriPekerjaan::create(['nama_kategori' => 'Pekerjaan Tanah & Pondasi']);
        $kategori2 = KategoriPekerjaan::create(['nama_kategori' => 'Pekerjaan Dinding & Plesteran']);
        $kategori3 = KategoriPekerjaan::create(['nama_kategori' => 'Pekerjaan Beton & Struktur']);
        $kategori4 = KategoriPekerjaan::create(['nama_kategori' => 'Pekerjaan Atap & Plafon']);
        $kategori5 = KategoriPekerjaan::create(['nama_kategori' => 'Pekerjaan Lantai & Keramik']);
        $kategori6 = KategoriPekerjaan::create(['nama_kategori' => 'Pekerjaan Sanitasi & Instalasi Air']);
        $kategori7 = KategoriPekerjaan::create(['nama_kategori' => 'Pekerjaan Pengecatan']);
        $kategori8 = KategoriPekerjaan::create(['nama_kategori' => 'Lain-lain']);

        // Update existing pekerjaan to have default kategori
        Pekerjaan::whereNull('kategori_pekerjaan_id')->update([
            'kategori_pekerjaan_id' => $kategori8->id
        ]);
    }
}
