<?php

namespace App\Http\Controllers;

use App\Models\Income;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IncomeController extends Controller
{
    public function index()
    {
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
            if($validated['amount'] <= 0) {
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
