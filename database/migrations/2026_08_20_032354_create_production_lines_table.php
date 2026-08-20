<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('production_lines', function (Blueprint $table) {
            $table->id();

            $table->foreignId('parent_id')->nullable()
                ->constrained('production_lines')
                ->cascadeOnDelete();

            $table->string('code', 30)->unique();
            $table->string('name');
            $table->unsignedInteger('order_no')->default(0);
            $table->timestamps();
        });

        // ---------- Seed data dasar ----------
        $inhouseId = DB::table('production_lines')->insertGetId([
            'parent_id' => null,
            'code' => 'inhouse',
            'name' => 'Inhouse',
            'order_no' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('production_lines')->insert([
            [
                'parent_id' => $inhouseId,
                'code' => 'inhouse_stripping',
                'name' => 'Stripping',
                'order_no' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'parent_id' => $inhouseId,
                'code' => 'inhouse_bottling',
                'name' => 'Bottling',
                'order_no' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'parent_id' => null,
                'code' => 'toll_out',
                'name' => 'Toll Out',
                'order_no' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('production_lines');
    }
};