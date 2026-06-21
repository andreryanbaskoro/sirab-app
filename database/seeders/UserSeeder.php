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

        // 1 Kepala Tukang
        $tukang = User::create([
            'name' => 'Tukang',
            'email' => 'tukang@tukang.com',
            'password' => Hash::make('password'),
            'role' => 'kepala_tukang',
            'status_aktif' => true,
        ]);
        $tukang->assignRole('kepala_tukang');
        $tukang->profile()->create([
            'alamat' => 'Jl. Merdeka No 1',
            'no_hp' => '081234567890'
        ]);

        // 1 Konsumen
        $konsumen = User::create([
            'name' => 'Konsumen',
            'email' => 'konsumen@konsumen.com',
            'password' => Hash::make('password'),
            'role' => 'konsumen',
            'status_aktif' => true,
        ]);
        $konsumen->assignRole('konsumen');
        $konsumen->profile()->create([
            'alamat' => 'Jl. Merdeka No 1',
            'no_hp' => '081234567890'
        ]);
    }
}