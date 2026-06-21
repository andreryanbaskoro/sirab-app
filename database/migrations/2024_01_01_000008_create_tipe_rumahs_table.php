<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('tipe_rumahs', function (Blueprint $table) {
            $table->id();
            $table->string('nama_tipe');
            $table->decimal('luas', 10, 2);
            $table->text('deskripsi')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('tipe_rumahs');
    }
};
