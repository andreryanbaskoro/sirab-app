<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pembayarans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kontrak_id')->constrained('kontraks')->cascadeOnDelete();
            $table->string('termin'); // e.g. "DP 30%", "Termin 1 40%", "Pelunasan"
            $table->decimal('jumlah', 15, 2);
            $table->string('bukti_transfer')->nullable();
            $table->text('keterangan')->nullable();
            $table->string('status')->default('menunggu_verifikasi'); // menunggu_verifikasi, diverifikasi, ditolak
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembayarans');
    }
};
