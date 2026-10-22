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
        Schema::table('batches', function (Blueprint $table) {
            $table->string('batch_validasi', 10)->nullable()->after('keterangan');
            $table->string('laporan_validasi', 10)->nullable()->after('batch_validasi');
            $table->string('nomor_laporan_validasi', 255)->nullable()->after('laporan_validasi');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('batches', function (Blueprint $table) {
            $table->dropColumn(['batch_validasi', 'laporan_validasi', 'nomor_laporan_validasi']);
        });
    }
};
