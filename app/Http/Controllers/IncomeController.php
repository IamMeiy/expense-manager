<?php

namespace App\Http\Controllers;

use App\Models\Income;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class IncomeController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            return DataTables::of(Income::where('user_id', Auth::user()->id))
                ->filter(function ($query) use ($request) {
                    $from_date = $request->get('from_date');
                    $to_date = $request->get('to_date');
                    if ($from_date && $to_date) {
                        $query->whereBetween('received_at', [$from_date, $to_date]);
                    }
                })
                ->addColumn('actions', function ($income) {
                    $editUrl = route('income.edit', $income->id);
                    $deleteUrl = route('income.destroy', $income->id);
                    
                    return '
                        <a href="' . $editUrl . '" class="btn btn-primary">
                            <i class="ti ti-edit"></i> Edit
                        </a>
                        <button class="btn btn-danger delete-income" data-url="' . $deleteUrl . '">
                            <i class="ti ti-trash"></i> Delete
                        </button>
                    ';
                })
                ->editColumn('source', function ($income) {
                    return INCOME_SOURCES[$income->source_type];
                })
                ->editColumn('amount', function ($income) {
                    return number_format($income->amount, 2);
                })
                ->editColumn('received_at', function ($income) {
                    return $income->received_at->format('d-m-Y');
                })
                ->rawColumns(['actions'])
                
                ->make(true);
        }
        return view('income.index');
    }

    public function create()
    {
        return view('income.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'source' => ['required', 'integer', 'max:255'],
            'amount' => ['required', 'numeric'],
            'description' => ['nullable', 'string'],
            'date' => ['required', 'date'],
        ]);
        try {
            if ($validated['amount'] <= 0) {
                return response()->json(['message' => 'Amount must be greater than zero'], 422);
            }

            Income::create([
                'user_id' => Auth::user()->id,
                'source_type' => $validated['source'],
                'amount' => number_format($validated['amount'], 2, '.', ''),
                'description' => $validated['description'] ?? null,
                'received_at' => $validated['date'],
            ]);

            return response()->json(['message' => 'Income stored successfully'], 201);
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Failed to store income', 'error' => $th->getMessage()], 500);
        }
    }

    public function edit(Income $income)
    {
        try {
            if ($income->user_id !== Auth::user()->id) {
                return redirect()->route('income.index')->with('error', 'Unauthorized access');
            }
            return view('income.edit', compact('income'));
        } catch (\Throwable $th) {
            return redirect()->route('income.index')->with('error', 'Income not found');
        }
    }

    public function update(Request $request, Income $income)
    {
        $validated = $request->validate([
            'source' => ['required', 'integer', 'max:255'],
            'amount' => ['required', 'numeric'],
            'description' => ['nullable', 'string'],
            'date' => ['required', 'date'],
        ]);
        try {
            if ($income->user_id !== Auth::user()->id) {
                return response()->json(['message' => 'Unauthorized'], 403);
            }
            if ($validated['amount'] <= 0) {
                return response()->json(['message' => 'Amount must be greater than zero'], 422);
            }

            $income->update([
                'source_type' => $validated['source'],
                'amount' => number_format($validated['amount'], 2, '.', ''),
                'description' => $validated['description'] ?? null,
                'received_at' => $validated['date'],
            ]);

            return response()->json(['message' => 'Income updated successfully'], 200);
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Failed to update income', 'error' => $th->getMessage()], 500);
        }
    }

    public function destroy(Income $income)
    {
        try {
            if ($income->user_id !== Auth::user()->id) {
                return response()->json(['message' => 'Unauthorized'], 403);
            }
            $income->delete();

            return response()->json(['message' => 'Income deleted successfully'], 200);
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Failed to delete income', 'error' => $th->getMessage()], 500);
        }
    }
}
