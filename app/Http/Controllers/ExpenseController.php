<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class ExpenseController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            return DataTables::of(Expense::query()->where('user_id', Auth::id()))
                ->filter(function ($query) {
                    // get the datatables search value safely
                    $search = request('search')['value'] ?? null;
                    if ($search !== null && $search !== '') {
                        $query->where(function ($q) use ($search) {
                            $q->where('payee', 'like', "%{$search}%")
                                ->orWhere('amount', 'like', "%{$search}%");
                        });
                    }
                    if (!empty(request('from_date')) && !empty(request('to_date'))) {
                        $query->whereBetween('date', [request('from_date'), request('to_date')]);
                    }
                    if (!empty(request('expense_type'))) {
                        $query->where('expense_type_id', request('expense_type'));
                    }
                    if (!empty(request('payment_method'))) {
                        $query->where('payment_method_id', request('payment_method'));
                    }
                })
                ->addColumn('actions', function ($expense) {
                    $editUrl = route('expense.edit', $expense->id);
                    $deleteUrl = route('expense.destroy', $expense->id);
                    return '
                        <button data-url="' . $editUrl . '" class="btn btn-primary edit-expense">
                            <i class="ti ti-edit"></i> Edit
                        </button>
                        <button class="btn btn-danger delete-expense" data-url="' . $deleteUrl . '">
                            <i class="ti ti-trash"></i> Delete
                        </button>
                    ';
                })
                ->editColumn('amount', function ($expense) {
                    return number_format($expense->amount, 2);
                })
                ->editColumn('date', function ($expense) {
                    return $expense->date->format('d-m-Y');
                })
                ->editColumn('expense_type', function ($expense) {
                    return EXPENSE_TYPES[$expense->expense_type_id]['title'] ?? 'N/A';
                })
                ->editColumn('payment_method', function ($expense) {
                    return PAYMENT_METHODS[$expense->payment_method_id] ?? 'N/A';
                })
                ->orderColumn('expense_type', function ($query, $order) {
                    $query->orderBy('expense_type_id', $order);
                })
                ->orderColumn('payment_method', function ($query, $order) {
                    $query->orderBy('payment_method_id', $order);
                })
                ->rawColumns(['actions'])
                ->make(true);
        }
        return view('expense');
    }

    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'expense_type_id' => 'required|integer',
                'payment_method_id' => 'required|integer',
                'date' => 'required|date',
                'payee' => 'required|string|max:255',
                'amount' => 'required|numeric',
                'description' => 'nullable|string',
                'invoice' => 'nullable|string|max:255',
            ]);

            $validatedData['user_id'] = Auth::id();
            $validatedData['amount'] = number_format($validatedData['amount'], 2, '.', '');

            Expense::create($validatedData);

            return response()->json(['message' => 'Expense added successfully.'], 201);
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Failed to add expense.', 'error' => $th->getMessage()], 500);
        }
    }

    public function edit(Expense $expense)
    {
        try {
            if ($expense->user_id !== Auth::id()) {
                return response()->json(['message' => 'Unauthorized action.'], 403);
            }
            
            return response()->json(['expense' => $expense, 'url' => route('expense.update', ['expense' => $expense->id])], 200);
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Failed to fetch expense.', 'error' => $th->getMessage()], 500);
        }
    }

    public function update(Request $request, Expense $expense)
    {
        try {
            if ($expense->user_id !== Auth::id()) {
                return response()->json(['message' => 'Unauthorized action.'], 403);
            }

            $validatedData = $request->validate([
                'expense_type_id' => 'required|integer',
                'payment_method_id' => 'required|integer',
                'date' => 'required|date',
                'payee' => 'required|string|max:255',
                'amount' => 'required|numeric',
                'description' => 'nullable|string',
                'invoice' => 'nullable|string|max:255',
            ]);

            $validatedData['amount'] = number_format($validatedData['amount'], 2, '.', '');

            $expense->update($validatedData);

            return response()->json(['message' => 'Expense updated successfully.'], 200);
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Failed to update expense.', 'error' => $th->getMessage()], 500);
        }
    }

    public function destroy(Expense $expense)
    {
        try {
            if ($expense->user_id !== Auth::id()) {
                return response()->json(['message' => 'Unauthorized action.'], 403);
            }

            $expense->delete();

            return response()->json(['message' => 'Expense deleted successfully.'], 200);
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Failed to delete expense.', 'error' => $th->getMessage()], 500);
        }
    }
}
