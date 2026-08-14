<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('checklist_questions', function (Blueprint $table) {
            $table->index('checklist_category_id', 'checklist_questions_category_id_index');
        });

        Schema::table('checklist_questions', function (Blueprint $table) {
            $table->dropUnique(['checklist_category_id', 'order_no']);

            $table->foreignId('parent_id')->nullable()->after('checklist_category_id')
                ->constrained('checklist_questions')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('checklist_questions', function (Blueprint $table) {
            $table->dropConstrainedForeignId('parent_id');
            $table->unique(['checklist_category_id', 'order_no']);
        });

        Schema::table('checklist_questions', function (Blueprint $table) {
            $table->dropIndex('checklist_questions_category_id_index');
        });
    }
};