<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('validasis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('permintaan_id')->constrained()->cascadeOnDelete();
            $table->foreignId('dari_user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('ke_user_id')->constrained('users')->cascadeOnDelete();
            $table->string('status');
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('validasis');
    }
};
