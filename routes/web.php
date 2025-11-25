<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\IncomeController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\BankAccountController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    /* Belowed routes for the users */
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::get('/users/{user}', [UserController::class, 'show'])->name('users.show');
    Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');

    /* Below routes for the income */
    Route::get('/income', [IncomeController::class, 'index'])->name('income.index');
    Route::get('/income/create', [IncomeController::class, 'create'])->name('income.create');
    Route::post('/income', [IncomeController::class, 'store'])->name('income.store');
    Route::get('/income/{income}', [IncomeController::class, 'show'])->name('income.show');
    Route::get('/income/{income}/edit', [IncomeController::class, 'edit'])->name('income.edit');
    Route::put('/income/{income}', [IncomeController::class, 'update'])->name('income.update');
    Route::delete('/income/{income}', [IncomeController::class, 'destroy'])->name('income.destroy');

    /* Below routes for the expense */
    Route::get('/expense', [ExpenseController::class, 'index'])->name('expense.index');
    Route::post('/expense', [ExpenseController::class, 'store'])->name('expense.store');
    Route::get('/expense/{expense}/edit', [ExpenseController::class, 'edit'])->name('expense.edit');
    Route::put('/expense/{expense}', [ExpenseController::class, 'update'])->name('expense.update');
    Route::delete('/expense/{expense}', [ExpenseController::class, 'destroy'])->name('expense.destroy');
    
    /* Below routes for the bank account */
    Route::prefix('bank-accounts')->group(function () {
        Route::get('/', [BankAccountController::class, 'index'])->name('bank-accounts.index');
        Route::post('/', [BankAccountController::class, 'store'])->name('bank-accounts.store');
        Route::get('/{bank_account}', [BankAccountController::class, 'show'])->name('bank-accounts.show');
        Route::get('/{bank_account}/edit', [BankAccountController::class, 'edit'])->name('bank-accounts.edit');
        Route::put('/{bank_account}', [BankAccountController::class, 'update'])->name('bank-accounts.update');
        Route::delete('/{bank_account}', [BankAccountController::class, 'destroy'])->name('bank-accounts.destroy');
    });
});

require __DIR__ . '/auth.php';
