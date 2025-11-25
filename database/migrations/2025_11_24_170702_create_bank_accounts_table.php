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
        Schema::create('bank_accounts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('account_type');
            $table->unsignedBigInteger('user_id');
            $table->string('bank_name');
            $table->string('account_number');
            $table->timestamps();
            $table->softDeletes();

            $table->index(['user_id', 'account_type'], 'idx_user_account_type');
            $table->index(['user_id', 'account_number'], 'idx_user_account_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bank_accounts');
    }
};
