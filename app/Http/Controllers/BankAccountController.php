<?php

namespace App\Http\Controllers;

use App\Models\BankAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Yajra\DataTables\Facades\DataTables;

class BankAccountController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            return DataTables::of(BankAccount::query())
                ->addIndexColumn()
                ->filter(function($query) use ($request) {
                    $search = request('search')['value'] ?? null;
                    if ($search !== null && $search !== '') {
                        $query->where(function ($q) use ($search) {
                            $q->where('bank_name', 'like', "%{$search}%")
                                ->orWhere('account_number', 'like', "%{$search}%");
                        });
                    }
                    
                    if($request->has('account_type') && !empty($request->account_type)) {
                        $query->where('account_type', $request->account_type);
                    }
                })
                ->addColumn('account_type', function ($bankAccount) {
                    return BANK_ACCOUNT_TYPES[$bankAccount->account_type] ?? 'N/A';
                })
                ->addColumn('account_number', function ($bankAccount) {
                    return substr($bankAccount->account_number, -4);
                })
                ->addColumn('balance', function ($bankAccount) {
                    // Placeholder for balance calculation logic
                    return '0';
                })
                ->addColumn('actions', function ($bankAccount) {
                    return view('bank.partials.account_actions', ['bankAccount' => $bankAccount])->render();
                })
                ->rawColumns(['actions'])
                ->make(true);
        }
        return view('bank.accounts');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'account_type' => 'required|integer',
            'bank_name' => 'required|string|max:255',
            'account_number' => ['required', 'string', 'max:255', Rule::unique('bank_accounts', 'account_number')->where(function ($query) {
                return $query->where('user_id', Auth::id())
                    ->whereNull('deleted_at');
            })],
        ]);
        try {
            $validated['user_id'] = Auth::id();

            BankAccount::create($validated);
            return response()->json(['message' => 'Bank account created successfully.'], 201);
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Failed to create bank account.', 'error' => $th->getMessage()], 500);
        }
    }

    public function edit($account)
    {
        try {
            $bankAccount = BankAccount::findOrFail(decrypt($account));
            if ($bankAccount->user_id !== Auth::id()) {
                return response()->json(['message' => 'Unauthorized action.'], 403);
            }
            return response()->json(['data' => $bankAccount, 'url' => route('bank-accounts.update', encrypt($bankAccount->id))], 200);
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Failed to fetch bank account details.', 'error' => $th->getMessage()], 500);
        }
    }

    public function update(Request $request, $account)
    {
        $validated = $request->validate([
            'account_type' => 'required|integer',
            'bank_name' => 'required|string|max:255',
            'account_number' => ['required', 'string', 'max:255', Rule::unique('bank_accounts', 'account_number')->where(function ($query) {
                return $query->where('user_id', Auth::id())
                    ->whereNull('deleted_at');
            })->ignore(decrypt($account))],
        ]);
        try {
            $bankAccount = BankAccount::findOrFail(decrypt($account));
            if ($bankAccount->user_id !== Auth::id()) {
                return response()->json(['message' => 'Unauthorized action.'], 403);
            }
            $bankAccount->update($validated);
            return response()->json(['message' => 'Bank account updated successfully.'], 200);
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Failed to update bank account.', 'error' => $th->getMessage()], 500);
        }
    }

    public function destroy($account)
    {
        try {
            $bankAccount = BankAccount::findOrFail(decrypt($account));
            if ($bankAccount->user_id !== Auth::id()) {
                return response()->json(['message' => 'Unauthorized action.'], 403);
            }
            $bankAccount->delete();
            return response()->json(['message' => 'Bank account deleted successfully.'], 200);
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Failed to delete bank account.', 'error' => $th->getMessage()], 500);
        }
    }
}
