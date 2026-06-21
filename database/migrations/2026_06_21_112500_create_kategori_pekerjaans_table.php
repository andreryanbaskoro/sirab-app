<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kategori_pekerjaans', function (Blueprint $table) {
            $table->id();
            $table->string('nama_kategori');
            $table->timestamps();
        });

        Schema::table('pekerjaans', function (Blueprint $table) {
            $table->foreignId('kategori_pekerjaan_id')->nullable()->after('id')->constrained('kategori_pekerjaans')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('pekerjaans', function (Blueprint $table) {
            $table->dropForeign(['kategori_pekerjaan_id']);
            $table->dropColumn('kategori_pekerjaan_id');
        });

        Schema::dropIfExists('kategori_pekerjaans');
    }
};
