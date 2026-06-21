<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('kontraks', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_kontrak')->unique();
            $table->foreignId('permintaan_id')->constrained()->cascadeOnDelete();
            $table->foreignId('rab_id')->constrained()->cascadeOnDelete();
            $table->date('tanggal_mulai')->nullable();
            $table->date('tanggal_selesai')->nullable();
            $table->string('file_kontrak')->nullable();
            $table->string('status')->default('draft');
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('kontraks');
    }
};
