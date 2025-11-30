<?php

namespace App\Http\Controllers;

use App\Models\Saving;
use App\Models\BankAccount;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class SavingController extends Controller
{
    public function index(Request $request, BankAccount $bankAccount)
    {
        try {
            if ($request->ajax()) {
                return DataTables::of(Saving::query()->where('bank_account_id', $bankAccount->id))
                    ->addIndexColumn()
                    ->addColumn('saved_at', function ($saving) {
                        return Carbon::parse($saving->saved_at)->format('d-m-Y');
                    })
                    ->addColumn('balance', function ($saving) {
                        return '₹ ' . number_format($saving->amount - $saving->transfered_amount, 2);
                    })
                    ->addColumn('actions', function ($saving) {
                        return view('bank.partials.savings_actions', ['saving' => $saving])->render();
                    })
                    ->editColumn('amount', function ($saving) {
                        return '₹ ' . number_format($saving->amount, 2);
                    })
                    ->rawColumns(['actions'])
                    ->make(true);
            }
            return view('bank.savings', compact('bankAccount'));
        } catch (\Exception $e) {
            return abort(404);
        }
    }

    public function store(Request $request, BankAccount $bankAccount)
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'amount' => 'required|numeric',
            'description' => 'nullable|string',
        ]);
        try {
            if ($bankAccount->user_id !== Auth::user()->id) {
                return response()->json(['message' => 'Unauthorized action.'], 403);
            }

            $bankAccount->savings()->create([
                'user_id' => Auth::user()->id,
                'saved_at' => $validated['date'],
                'amount' => $validated['amount'],
                'description' => $validated['description'] ?? null
            ]);

            $amount = $this->calculateAmounts($bankAccount);

            return response()->json(['message' => 'Savings added successfully.', 'amount' => $amount], 201);
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Failed to add Savings.', 'error' => $th->getMessage()], 500);
        }
    }

    public function edit(BankAccount $bankAccount, Saving $saving)
    {
        try {
            if ($bankAccount->user_id !== Auth::id() || $saving->user_id !== Auth::id()) {
                return response()->json(['message' => 'Unauthorized action.'], 403);
            }
            return response()->json(['data' => $saving, 'url' => route('savings.update', [$bankAccount->id, $saving->id])], 200);
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Failed to fetch savings details.', 'error' => $th->getMessage()], 500);
        }
    }

    public function update(Request $request, BankAccount $bankAccount, Saving $saving)
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'amount' => 'required|numeric',
            'description' => 'nullable|string',
        ]);

        try {
            if ($bankAccount->user_id !== Auth::id() || $saving->user_id !== Auth::id()) {
                return response()->json(['message' => 'Unauthorized action.'], 403);
            }

            $saving->update([
                'saved_at' => $validated['date'],
                'amount' => $validated['amount'],
                'description' => $validated['description'] ?? null
            ]);

            $amount = $this->calculateAmounts($bankAccount);

            return response()->json(['message' => 'Savings updated successfully.', 'amount' => $amount], 201);
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Failed to update Savings.', 'error' => $th->getMessage()], 500);
        }
    }

    public function destroy(BankAccount $bankAccount, Saving $saving)
    {
        try {
            if ($bankAccount->user_id !== Auth::id() || $saving->user_id !== Auth::id()) {
                return response()->json(['message' => 'Unauthorized action.'], 403);
            }

            $saving->delete();

            $amount = $this->calculateAmounts($bankAccount);

            return response()->json(['message' => 'Savings deleted successfully.', 'amount' => $amount], 201);
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Failed to delete Savings.', 'error' => $th->getMessage()], 500);
        }
    }

    private function calculateAmounts($bankAccount): array
    {
        // Do math first with raw numbers
        $added = $bankAccount->savings->sum('amount');
        $transferred = $bankAccount->savings->sum('transfered_amount');
        $available = $added - $transferred;

        // Format for display
        return [
            'added'       => number_format($added, 2),
            'transferred' => number_format($transferred, 2),
            'available'   => number_format($available, 2),
        ];
    }
}
