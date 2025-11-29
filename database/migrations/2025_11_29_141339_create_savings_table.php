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
        Schema::create('savings', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('user_id');
            $table->uuid('bank_account_id');
            $table->date('saved_at');
            $table->decimal('amount', 15, 2);
            $table->text('description')->nullable();
            $table->decimal('transfered_amount', 15, 2)->nullable();
            $table->text('transfer_reason')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['user_id', 'bank_account_id'], 'idx_user_bank_account');
            $table->index(['user_id', 'saved_at'], 'idx_user_saved_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('savings');
    }
};
