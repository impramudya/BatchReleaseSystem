<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('batch_evaluation_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('batch_id')->constrained('batches')->cascadeOnDelete();
            $table->foreignId('checklist_question_id')->constrained('checklist_questions')->cascadeOnDelete();
            $table->enum('answer', ['C', 'NC', 'NA'])->nullable();
            $table->timestamps();

            $table->unique(['batch_id', 'checklist_question_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('batch_evaluation_answers');
    }
};