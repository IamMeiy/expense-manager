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
        Schema::table('incomes', function (Blueprint $table) {
            $table->dropColumn('source');
            $table->unsignedSmallInteger('source_type')->after('user_id');

            $table->index(['user_id', 'source_type'], 'idx_user_source_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('incomes', function (Blueprint $table) {
            $table->dropIndex('idx_user_source_type');

            $table->dropColumn('source_type');
            $table->string('source')->after('user_id');
        });
    }
};
