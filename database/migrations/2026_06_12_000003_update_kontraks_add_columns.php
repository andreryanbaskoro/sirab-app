<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('kontraks', function (Blueprint $table) {
            $table->foreignId('konsumen_id')->nullable()->after('rab_id')->constrained('users')->nullOnDelete();
            $table->foreignId('tukang_id')->nullable()->after('konsumen_id')->constrained('users')->nullOnDelete();
            $table->decimal('nilai_kontrak', 15, 2)->default(0)->after('tukang_id');
            $table->text('keterangan')->nullable()->after('nilai_kontrak');
            $table->softDeletes()->after('updated_at');
        });
    }
    public function down(): void {
        Schema::table('kontraks', function (Blueprint $table) {
            $table->dropForeign(['konsumen_id', 'tukang_id']);
            $table->dropColumn(['konsumen_id', 'tukang_id', 'nilai_kontrak', 'keterangan']);
            $table->dropSoftDeletes();
        });
    }
};
