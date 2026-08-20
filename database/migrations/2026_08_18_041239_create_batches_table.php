<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('batches', function (Blueprint $table) {
            $table->id();

            $table->foreignId('product_id')->constrained('products');

            $table->string('batch_number')->unique();
            $table->string('manufacturer');
            $table->enum('production_type', ['in_house', 'toll_out']);
            $table->date('batch_date');

            $table->foreignId('supervisor_id')->nullable()->constrained('users');

            $table->text('keterangan')->nullable();
            $table->enum('status', ['draft', 'submitted'])->default('draft');

            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('batches');
    }
};