<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('permintaans', function (Blueprint $table) {
            $table->string('nomor_permintaan')->unique()->nullable()->after('id');
            $table->string('dokumen_path')->nullable()->after('catatan');
            $table->text('alasan_tolak')->nullable()->after('dokumen_path');
            $table->softDeletes()->after('updated_at');
        });
    }
    public function down(): void {
        Schema::table('permintaans', function (Blueprint $table) {
            $table->dropColumn(['nomor_permintaan', 'dokumen_path', 'alasan_tolak']);
            $table->dropSoftDeletes();
        });
    }
};
