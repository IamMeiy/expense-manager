<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Check existing indexes
        $indexes = DB::table('information_schema.statistics')
            ->select('INDEX_NAME')
            ->where('TABLE_SCHEMA', DB::getDatabaseName())
            ->where('TABLE_NAME', 'savings')
            ->pluck('INDEX_NAME')
            ->toArray();

        // Drop indexes if they exist
        Schema::table('savings', function (Blueprint $table) use ($indexes) {
            if (in_array('idx_user_bank_account', $indexes)) {
                $table->dropIndex('idx_user_bank_account');
            }
            if (in_array('idx_user_saved_at', $indexes)) {
                $table->dropIndex('idx_user_saved_at');
            }
        });

        // Drop column if exists
        if (Schema::hasColumn('savings', 'user_id')) {
            Schema::table('savings', function (Blueprint $table) {
                $table->dropColumn('user_id');
            });
        }

        // Add new column and indexes
        Schema::table('savings', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->after('id');

            // Requires doctrine/dbal for ->change()
            $table->decimal('amount', 15, 2)->default(0)->change();
            $table->decimal('transfered_amount', 15, 2)->default(0)->change();

            $table->index(['user_id', 'bank_account_id'], 'idx_user_bank_account');
            $table->index(['user_id', 'saved_at'], 'idx_user_saved_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Check existing indexes
        $indexes = DB::table('information_schema.statistics')
            ->select('INDEX_NAME')
            ->where('TABLE_SCHEMA', DB::getDatabaseName())
            ->where('TABLE_NAME', 'savings')
            ->pluck('INDEX_NAME')
            ->toArray();

        // Drop indexes if they exist
        Schema::table('savings', function (Blueprint $table) use ($indexes) {
            if (in_array('idx_user_bank_account', $indexes)) {
                $table->dropIndex('idx_user_bank_account');
            }
            if (in_array('idx_user_saved_at', $indexes)) {
                $table->dropIndex('idx_user_saved_at');
            }
        });

        // Drop column if exists
        if (Schema::hasColumn('savings', 'user_id')) {
            Schema::table('savings', function (Blueprint $table) {
                $table->dropColumn('user_id');
            });
        }

        // Add back uuid column and indexes
        Schema::table('savings', function (Blueprint $table) {
            $table->uuid('user_id')->after('id');
            $table->index(['user_id', 'bank_account_id'], 'idx_user_bank_account');
            $table->index(['user_id', 'saved_at'], 'idx_user_saved_at');
        });
    }
};
