<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('batches', function (Blueprint $table) {
            $table->string('creator_name')->nullable()->after('created_by');
            $table->string('supervisor_name')->nullable()->after('supervisor_id');
            $table->string('manager_name')->nullable()->after('manager_id');
        });

        Schema::table('batch_approvals', function (Blueprint $table) {
            $table->string('user_name')->nullable()->after('user_id');
        });

        // Backfill existing batches
        $batches = DB::table('batches')->get();
        foreach ($batches as $b) {
            $creator = $b->created_by ? DB::table('users')->where('id', $b->created_by)->value('name') : null;
            $supervisor = $b->supervisor_id ? DB::table('users')->where('id', $b->supervisor_id)->value('name') : null;
            $manager = $b->manager_id ? DB::table('users')->where('id', $b->manager_id)->value('name') : null;

            DB::table('batches')->where('id', $b->id)->update([
                'creator_name' => $creator,
                'supervisor_name' => $supervisor,
                'manager_name' => $manager,
            ]);
        }

        // Backfill existing batch approvals
        $approvals = DB::table('batch_approvals')->get();
        foreach ($approvals as $a) {
            $userName = $a->user_id ? DB::table('users')->where('id', $a->user_id)->value('name') : null;
            if ($userName) {
                DB::table('batch_approvals')->where('id', $a->id)->update([
                    'user_name' => $userName,
                ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('batch_approvals', function (Blueprint $table) {
            $table->dropColumn('user_name');
        });

        Schema::table('batches', function (Blueprint $table) {
            $table->dropColumn(['creator_name', 'supervisor_name', 'manager_name']);
        });
    }
};
