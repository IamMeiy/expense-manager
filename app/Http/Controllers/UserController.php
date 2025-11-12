<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            return DataTables::of(User::query())
                ->filter(function ($query) {
                    if (request()->has('search')) {
                        $query->where('id', 'like', '%' . request('search')['value'] . '%')
                            ->orWhere('name', 'like', '%' . request('search')['value'] . '%')
                            ->orWhere('email', 'like', '%' . request('search')['value'] . '%');
                    }
                })
                ->addColumn('id', function ($user) {
                    return $user->id;
                })
                ->addColumn('name', function ($user) {
                    return $user->name;
                })
                ->addColumn('email', function ($user) {
                    return $user->email;
                })
                ->addColumn('action', function ($user) {
                    $editUrl = route('users.edit', encrypt($user->id));
                    $deleteUrl = route('users.destroy', encrypt($user->id));
                    return '
                        <a href="' . $editUrl . '" class="btn btn-sm btn-primary">
                            <i class="ti ti-edit"></i> Edit
                        </a>
                        <button class="btn btn-sm btn-danger" data-url="' . $deleteUrl . '">
                            <i class="ti ti-trash"></i> Delete
                            </button>
                    ';
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('users.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('users.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->whereNull('deleted_at')],
            'password' => ['required', 'string', 'min:8'],
        ]);

        try {
            User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
            ]);
            return response()->json(['message' => 'User created successfully'], 201);
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Failed to create user', 'error' => $th->getMessage(), 'trace' => $th->getTrace()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        try {
            $user->delete();
            return response()->json(['message' => 'User deleted successfully'], 200);
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Failed to delete user', 'error' => $th->getMessage(), 'trace' => $th->getTrace()], 500);
        }
    }
}
