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
        Schema::table('rabs', function (Blueprint $table) {
            $table->decimal('total_sebelum_pajak', 15, 2)->default(0)->after('biaya_tambahan');
            $table->decimal('profit_persen', 5, 2)->default(0)->after('total_sebelum_pajak');
            $table->decimal('profit_nominal', 15, 2)->default(0)->after('profit_persen');
            $table->decimal('ppn_persen', 5, 2)->default(0)->after('profit_nominal');
            $table->decimal('ppn_nominal', 15, 2)->default(0)->after('ppn_persen');
        });

        Schema::table('rab_details', function (Blueprint $table) {
            $table->foreignId('parent_id')->nullable()->after('rab_id')->constrained('rab_details')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rabs_tables', function (Blueprint $table) {
            //
        });
    }
};
