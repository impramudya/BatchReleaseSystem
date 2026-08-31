<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('batches', function (Blueprint $table) {
            $table->foreignId('manager_id')->nullable()->after('supervisor_id')
                ->constrained('users')->nullOnDelete();

            $table->timestamp('released_at')->nullable()->after('status');
        });

        DB::statement("ALTER TABLE batches MODIFY status ENUM('draft', 'submitted', 'waiting_manager', 'released', 'rejected') NOT NULL DEFAULT 'draft'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE batches MODIFY status ENUM('draft', 'submitted') NOT NULL DEFAULT 'draft'");

        Schema::table('batches', function (Blueprint $table) {
            $table->dropConstrainedForeignId('manager_id');
            $table->dropColumn('released_at');
        });
    }
};
