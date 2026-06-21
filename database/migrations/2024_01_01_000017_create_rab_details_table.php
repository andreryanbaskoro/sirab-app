<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('rab_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rab_id')->constrained()->cascadeOnDelete();
            $table->enum('jenis_item', ['material', 'pekerjaan', 'upah', 'jasa_tukang', 'tambahan']);
            $table->unsignedBigInteger('referensi_id')->nullable();
            $table->string('nama_item');
            $table->decimal('qty', 10, 2)->default(0);
            $table->string('satuan');
            $table->decimal('harga_satuan', 15, 2)->default(0);
            $table->decimal('subtotal', 15, 2)->default(0);
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('rab_details');
    }
};
