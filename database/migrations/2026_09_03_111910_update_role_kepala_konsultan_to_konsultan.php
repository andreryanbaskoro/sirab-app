<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add new enum value first to avoid data loss
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin_pu', 'kepala_konsultan', 'konsultan', 'konsumen') DEFAULT 'konsumen'");
        
        // Update existing data
        DB::table('users')->where('role', 'kepala_konsultan')->update(['role' => 'konsultan']);
        
        // Remove old enum value
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin_pu', 'konsultan', 'konsumen') DEFAULT 'konsumen'");

        // Update spatie roles
        DB::table('roles')->where('name', 'kepala_konsultan')->update(['name' => 'konsultan']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin_pu', 'kepala_konsultan', 'konsultan', 'konsumen') DEFAULT 'konsumen'");
        DB::table('users')->where('role', 'konsultan')->update(['role' => 'kepala_konsultan']);
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin_pu', 'kepala_konsultan', 'konsumen') DEFAULT 'konsumen'");

        DB::table('roles')->where('name', 'konsultan')->update(['name' => 'kepala_konsultan']);
    }
};
