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
        Schema::table('expenses', function (Blueprint $table) {

            $table->unsignedBigInteger('bank_account_id')->nullable()->after('payment_method_id');
            $table->unsignedBigInteger('credit_card_id')->nullable()->after('bank_account_id');
            $table->unsignedBigInteger('loan_id')->nullable()->after('credit_card_id');

            $table->index(['user_id', 'date'], 'idx_user_date');
            $table->index(['user_id', 'expense_type_id'], 'idx_user_expense_type');
            $table->index(['user_id', 'payment_method_id'], 'idx_user_payment_method');
            $table->index(['user_id', 'bank_account_id'], 'idx_user_bank_account');
            $table->index(['user_id', 'credit_card_id'], 'idx_user_credit_card');
            $table->index(['user_id', 'loan_id'], 'idx_user_loan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('expenses', function (Blueprint $table) {
            $table->dropIndex('idx_user_date');
            $table->dropIndex('idx_user_expense_type');
            $table->dropIndex('idx_user_payment_method');
            $table->dropIndex('idx_user_bank_account');
            $table->dropIndex('idx_user_credit_card');
            $table->dropIndex('idx_user_loan');

            $table->dropColumn(['bank_account_id', 'credit_card_id', 'loan_id']);
        });
    }
};
