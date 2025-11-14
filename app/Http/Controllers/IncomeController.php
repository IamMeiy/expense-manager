<?php

namespace App\Http\Controllers;

use App\Models\Income;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class IncomeController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            return DataTables::of(Income::where('user_id', Auth::user()->id))
                ->addColumn('actions', function ($income) {
                    $editUrl = route('income.edit', encrypt($income->id));
                    $deleteUrl = route('income.destroy', encrypt($income->id));
                    
                    return '
                        <a href="' . $editUrl . '" class="btn btn-primary">
                            <i class="ti ti-edit"></i> Edit
                        </a>
                        <button class="btn btn-danger delete-income" data-url="' . $deleteUrl . '">
                            <i class="ti ti-trash"></i> Delete
                        </button>
                    ';
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
            'source' => ['required', 'string', 'max:255'],
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
                'source' => $validated['source'],
                'amount' => $validated['amount'],
                'description' => $validated['description'] ?? null,
                'received_at' => $validated['date'],
            ]);

            return response()->json(['message' => 'Income stored successfully'], 201);
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Failed to store income', 'error' => $th->getMessage(), 'trace' => $th->getTrace()], 500);
        }
    }
}
