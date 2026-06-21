<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('rabs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('permintaan_id')->constrained()->cascadeOnDelete();
            $table->foreignId('tukang_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('jasa_tukang_id')->nullable()->constrained('harga_jasa_tukangs')->nullOnDelete();
            $table->decimal('biaya_jasa_tukang', 15, 2)->default(0);
            $table->decimal('biaya_tambahan', 15, 2)->default(0);
            $table->decimal('total_material', 15, 2)->default(0);
            $table->decimal('total_upah', 15, 2)->default(0);
            $table->decimal('total_final', 15, 2)->default(0);
            $table->string('status')->default('draft');
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('rabs');
    }
};
