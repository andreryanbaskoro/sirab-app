<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('rabs', function (Blueprint $table) {
            $table->string('nomor_rab')->unique()->nullable()->after('id');
            $table->text('catatan_tukang')->nullable()->after('total_final');
            $table->text('alasan_tolak')->nullable()->after('catatan_tukang');
            $table->softDeletes()->after('updated_at');
        });
    }
    public function down(): void {
        Schema::table('rabs', function (Blueprint $table) {
            $table->dropColumn(['nomor_rab', 'catatan_tukang', 'alasan_tolak']);
            $table->dropSoftDeletes();
        });
    }
};
