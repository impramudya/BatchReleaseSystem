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
        // 1. Batches table indexes
        Schema::table('batches', function (Blueprint $table) {
            $table->index('status', 'batches_status_idx');
            $table->index('created_at', 'batches_created_at_idx');
            $table->index('batch_date', 'batches_batch_date_idx');
            $table->index('released_at', 'batches_released_at_idx');
            $table->index(['status', 'supervisor_id'], 'batches_status_spv_idx');
            $table->index(['status', 'manager_id'], 'batches_status_mgr_idx');
        });

        // 2. Batch Approvals table indexes
        Schema::table('batch_approvals', function (Blueprint $table) {
            $table->index('status', 'batch_approvals_status_idx');
            $table->index('step', 'batch_approvals_step_idx');
            $table->index('acted_at', 'batch_approvals_acted_at_idx');
            $table->index(['step', 'status'], 'batch_approvals_step_status_idx');
            $table->index(['user_id', 'status'], 'batch_approvals_user_status_idx');
        });

        // 3. Login Logs table indexes
        Schema::table('login_logs', function (Blueprint $table) {
            $table->index('created_at', 'login_logs_created_at_idx');
            $table->index('action', 'login_logs_action_idx');
            $table->index(['user_id', 'created_at'], 'login_logs_user_created_idx');
        });

        // 4. Activity Logs table indexes
        Schema::table('activity_logs', function (Blueprint $table) {
            $table->index('created_at', 'activity_logs_created_at_idx');
            $table->index('action', 'activity_logs_action_idx');
            $table->index(['user_id', 'created_at'], 'activity_logs_user_created_idx');
        });

        // 5. Users table indexes
        Schema::table('users', function (Blueprint $table) {
            $table->index('role', 'users_role_idx');
            $table->index('status', 'users_status_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('batches', function (Blueprint $table) {
            $table->dropIndex('batches_status_idx');
            $table->dropIndex('batches_created_at_idx');
            $table->dropIndex('batches_batch_date_idx');
            $table->dropIndex('batches_released_at_idx');
            $table->dropIndex('batches_status_spv_idx');
            $table->dropIndex('batches_status_mgr_idx');
        });

        Schema::table('batch_approvals', function (Blueprint $table) {
            $table->dropIndex('batch_approvals_status_idx');
            $table->dropIndex('batch_approvals_step_idx');
            $table->dropIndex('batch_approvals_acted_at_idx');
            $table->dropIndex('batch_approvals_step_status_idx');
            $table->dropIndex('batch_approvals_user_status_idx');
        });

        Schema::table('login_logs', function (Blueprint $table) {
            $table->dropIndex('login_logs_created_at_idx');
            $table->dropIndex('login_logs_action_idx');
            $table->dropIndex('login_logs_user_created_idx');
        });

        Schema::table('activity_logs', function (Blueprint $table) {
            $table->dropIndex('activity_logs_created_at_idx');
            $table->dropIndex('activity_logs_action_idx');
            $table->dropIndex('activity_logs_user_created_idx');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex('users_role_idx');
            $table->dropIndex('users_status_idx');
        });
    }
};
