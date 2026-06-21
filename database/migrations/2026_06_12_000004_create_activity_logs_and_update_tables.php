<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        if (!Schema::hasTable('activity_logs')) {
            Schema::create('activity_logs', function (Blueprint $table) {
                $table->id();
                $table->string('nomor_log')->unique();
                $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
                $table->string('subject_type')->nullable();
                $table->unsignedBigInteger('subject_id')->nullable();
                $table->string('action');
                $table->text('description');
                $table->json('properties')->nullable();
                $table->string('ip_address')->nullable();
                $table->timestamps();
                $table->index(['subject_type', 'subject_id']);
            });
        }

        // profiles already has 'foto' column, so we skip adding foto_profil
        // The 'foto' column in profiles will be used for profile photos

        // Add SoftDeletes to materials, pekerjaans, tipe_rumahs
        Schema::table('materials', function (Blueprint $table) {
            if (!Schema::hasColumn('materials', 'deleted_at')) {
                $table->softDeletes();
            }
        });
        Schema::table('pekerjaans', function (Blueprint $table) {
            if (!Schema::hasColumn('pekerjaans', 'deleted_at')) {
                $table->softDeletes();
            }
        });
        Schema::table('tipe_rumahs', function (Blueprint $table) {
            if (!Schema::hasColumn('tipe_rumahs', 'deleted_at')) {
                $table->softDeletes();
            }
        });
    }
    public function down(): void {
        Schema::dropIfExists('activity_logs');
    }
};
