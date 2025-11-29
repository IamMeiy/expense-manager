<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // drop children first
        Schema::dropIfExists('incomes');
        Schema::dropIfExists('expenses');
        Schema::dropIfExists('bank_accounts');
        Schema::dropIfExists('users');

        /*
         * USERS table (integer PK + public uuid)
         */
        Schema::create('users', function (Blueprint $table) {
            $table->id(); // integer primary key (keeps Laravel auth stable)
            $table->string('name')->nullable();
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
        });

        /*
         * BANK ACCOUNTS (UUID PK) - belongs to user (int id)
         */
        Schema::create('bank_accounts', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->unsignedBigInteger('user_id'); // references users.id (int)
            $table->unsignedSmallInteger('account_type')->nullable();
            $table->string('bank_name')->nullable();
            $table->string('account_number')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // indexes
            $table->index('user_id', 'idx_bank_accounts_user_id');
            $table->index(['user_id', 'account_type'], 'idx_bank_accounts_user_account_type');
            $table->unique(['user_id', 'account_number'], 'uq_bank_accounts_user_account_number');
        });

        /*
         * INCOMES (UUID PK) - belongs to user (int id)
         */
        Schema::create('incomes', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->unsignedBigInteger('user_id'); // int FK to users.id
            $table->string('source');
            $table->decimal('amount', 15, 2);
            $table->text('description')->nullable();
            $table->date('received_at');
            $table->timestamps();
            $table->softDeletes();

            // indexes
            $table->index('user_id', 'idx_incomes_user_id');              // admin user-only queries
            $table->index(['user_id', 'received_at'], 'idx_incomes_user_date'); // user + date filters
        });

        /*
         * EXPENSES (UUID PK) - belongs to user (int id), references bank_accounts by UUID
         */
        Schema::create('expenses', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->unsignedBigInteger('user_id'); // int FK to users.id

            $table->unsignedSmallInteger('expense_type_id');
            $table->unsignedSmallInteger('payment_method_id');

            $table->uuid('bank_account_id')->nullable();  // bank_accounts.id is UUID
            $table->uuid('credit_card_id')->nullable();   // if credit_cards are UUID PK
            $table->uuid('loan_id')->nullable();          // if loans are UUID PK

            $table->date('date');
            $table->string('payee')->nullable();
            $table->decimal('amount', 10, 2);
            $table->text('description')->nullable();
            $table->string('invoice')->nullable();

            $table->timestamps();
            $table->softDeletes();

            // Indexes: single user_id for admin + composites for filters
            $table->index('user_id', 'idx_expenses_user_id');
            $table->index(['user_id', 'date'], 'idx_expenses_user_date');
            $table->index(['user_id', 'expense_type_id'], 'idx_expenses_user_expense_type');
            $table->index(['user_id', 'payment_method_id'], 'idx_expenses_user_payment_method');
            $table->index(['user_id', 'bank_account_id'], 'idx_expenses_user_bank_account');
            $table->index(['user_id', 'credit_card_id'], 'idx_expenses_user_credit_card');
            $table->index(['user_id', 'loan_id'], 'idx_expenses_user_loan');
        });
    }

    public function down(): void
    {
        // drop in reverse order
        Schema::dropIfExists('incomes');
        Schema::dropIfExists('expenses');
        Schema::dropIfExists('bank_accounts');
        Schema::dropIfExists('users');

        // recreate original integer-based tables (fallback)
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('bank_accounts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedSmallInteger('account_type')->nullable();
            $table->string('bank_name')->nullable();
            $table->string('account_number')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('user_id', 'idx_bank_accounts_user_id');
            $table->index(['user_id', 'account_type'], 'idx_bank_accounts_user_account_type');
            $table->index(['user_id', 'account_number'], 'idx_bank_accounts_user_account_number');
        });

        Schema::create('incomes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('source');
            $table->decimal('amount', 15, 2);
            $table->text('description')->nullable();
            $table->date('received_at');
            $table->timestamps();
            $table->softDeletes();

            $table->index('user_id', 'idx_incomes_user_id');
            $table->index(['user_id', 'received_at'], 'idx_incomes_user_date');
        });

        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');

            $table->unsignedSmallInteger('expense_type_id');
            $table->unsignedSmallInteger('payment_method_id');

            $table->unsignedBigInteger('bank_account_id')->nullable();
            $table->unsignedBigInteger('credit_card_id')->nullable();
            $table->unsignedBigInteger('loan_id')->nullable();

            $table->date('date');
            $table->string('payee')->nullable();
            $table->decimal('amount', 10, 2);
            $table->text('description')->nullable();
            $table->string('invoice')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->index('user_id', 'idx_expenses_user_id');
            $table->index(['user_id', 'date'], 'idx_expenses_user_date');
            $table->index(['user_id', 'expense_type_id'], 'idx_expenses_user_expense_type');
            $table->index(['user_id', 'payment_method_id'], 'idx_expenses_user_payment_method');
            $table->index(['user_id', 'bank_account_id'], 'idx_expenses_user_bank_account');
            $table->index(['user_id', 'credit_card_id'], 'idx_expenses_user_credit_card');
            $table->index(['user_id', 'loan_id'], 'idx_expenses_user_loan');
        });
    }
};
