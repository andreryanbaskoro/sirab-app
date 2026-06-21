<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('permintaans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('konsumen_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('tukang_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('tipe_rumah_id')->constrained('tipe_rumahs')->cascadeOnDelete();
            $table->string('lokasi_proyek');
            $table->decimal('luas_bangunan', 10, 2);
            $table->text('catatan')->nullable();
            $table->string('status')->default('pending');
            $table->date('tanggal_permohonan');
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('permintaans');
    }
};
