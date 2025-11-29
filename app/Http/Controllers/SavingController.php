<?php

namespace App\Http\Controllers;

use App\Models\Saving;
use App\Models\BankAccount;
use Illuminate\Http\Request;

class SavingController extends Controller
{
    public function index(BankAccount $bankAccount)
    {
        try {
            $savings = Saving::where('bank_account_id', $bankAccount)->get();
            return view('bank.savings');
        } catch (\Exception $e) {
            return abort(404);
        }
    }
}
