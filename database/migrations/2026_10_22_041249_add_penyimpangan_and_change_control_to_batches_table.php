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
            $table->string('laporan_penyimpangan')->nullable()->after('nomor_laporan_validasi');
            $table->json('penyimpangan_items')->nullable()->after('laporan_penyimpangan');
            $table->string('change_control')->nullable()->after('penyimpangan_items');
            $table->json('change_control_items')->nullable()->after('change_control');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('batches', function (Blueprint $table) {
            $table->dropColumn([
                'laporan_penyimpangan',
                'penyimpangan_items',
                'change_control',
                'change_control_items',
            ]);
        });
    }
};
