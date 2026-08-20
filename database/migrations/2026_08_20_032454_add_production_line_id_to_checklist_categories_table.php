<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('checklist_categories', function (Blueprint $table) {
            $table->foreignId('production_line_id')->nullable()->after('id')
                ->constrained('production_lines')
                ->cascadeOnDelete();
        });

        Schema::table('checklist_categories', function (Blueprint $table) {
            $table->dropUnique(['code']);
            $table->unique(['production_line_id', 'code']);
        });
    }

    public function down(): void
    {
        Schema::table('checklist_categories', function (Blueprint $table) {
            $table->dropUnique(['production_line_id', 'code']);
            $table->unique('code');
        });

        Schema::table('checklist_categories', function (Blueprint $table) {
            $table->dropConstrainedForeignId('production_line_id');
        });
    }
};