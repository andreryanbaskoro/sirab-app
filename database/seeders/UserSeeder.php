<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // 1 Admin PU
        $admin = User::create([
            'name' => 'Admin PU',
            'email' => 'admin@pu.com',
            'password' => Hash::make('password'),
            'role' => 'admin_pu',
            'status_aktif' => true,
        ]);
        $admin->assignRole('admin_pu');

        // 2 Kepala Tukang
        $tukang1 = User::create([
            'name' => 'Tukang',
            'email' => 'tukang@tukang.com',
            'password' => Hash::make('password'),
            'role' => 'kepala_tukang',
            'status_aktif' => true,
        ]);
        $tukang1->assignRole('kepala_tukang');
        $tukang1->profile()->create(['alamat' => 'Jl. Merdeka No 1', 'no_hp' => '081234567890']);

        // 3 Konsumen
        for ($i = 1; $i <= 3; $i++) {
            $konsumen = User::create([
                'name' => 'Konsumen',
                'email' => 'konsumen@konsumen.com',
                'password' => Hash::make('password'),
                'role' => 'konsumen',
                'status_aktif' => true,
            ]);
            $konsumen->assignRole('konsumen');
            $konsumen->profile()->create(['alamat' => 'Jl. Merdeka No 1', 'no_hp' => '081234567890']);
        }
    }
}
