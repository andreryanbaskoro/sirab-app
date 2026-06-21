<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create roles
        $admin = Role::create(['name' => 'admin_pu']);
        $tukang = Role::create(['name' => 'kepala_tukang']);
        $konsumen = Role::create(['name' => 'konsumen']);
        
        // Optional: define permissions here if needed
        // For now, role-based checks are enough based on the prompt.
    }
}
