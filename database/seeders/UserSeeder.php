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

        // 1 Konsultan
        $konsultan = User::create([
            'name' => 'Konsultan',
            'email' => 'konsultan@konsultan.com',
            'password' => Hash::make('konsultan'),
            'role' => 'konsultan',
            'status_aktif' => true,
        ]);
        $konsultan->assignRole('konsultan');
        $konsultan->profile()->create([
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