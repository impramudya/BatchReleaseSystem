<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('checklist_questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('checklist_category_id')
                ->constrained('checklist_categories')
                ->cascadeOnDelete();
            $table->unsignedInteger('order_no');
            $table->text('question');
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();

            // Nomor urut harus unik di dalam satu kategori yang sama.
            $table->unique(['checklist_category_id', 'order_no']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('checklist_questions');
    }
};
