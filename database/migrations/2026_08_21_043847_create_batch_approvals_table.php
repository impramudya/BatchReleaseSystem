<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('batch_approvals', function (Blueprint $table) {
            $table->id();

            $table->foreignId('batch_id')->constrained('batches')->cascadeOnDelete();

            $table->enum('step', ['admin', 'supervisor', 'manager']);
            $table->unsignedTinyInteger('order_no');

            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->enum('status', ['approved', 'pending', 'rejected'])->default('pending');

            $table->text('note')->nullable();
            $table->timestamp('acted_at')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('batch_approvals');
    }
};
