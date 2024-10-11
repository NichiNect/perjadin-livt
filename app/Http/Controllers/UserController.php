<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->get('search', '');

        $user = User::with('role')
            ->when($search != '', function ($q) use ($search) {
                // return $q->whereAny(['name', 'email'], 'LIKE', "%$search%");
                return $q->where('name', 'LIKE', '%' . $search . '%')
                    ->orWhere('email', 'LIKE', '%' . $search . '%');
            })
            ->paginate(10);

        return Inertia::render('UserManagement/Index', [
            'user' => $user
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users,email',
            'role_id' => 'required|numeric|exists:roles,id',
            'password' => 'required|string|min:3'
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'role_id' => $request->role_id,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('users.index')->with([
            'message' => 'User created successfuly.',
            'class' => 'bg-success'
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users,email,' . $user->id,
            'role_id' => 'required|numeric|exists:roles,id',
            'password' => 'nullable|string|min:3'
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->role_id = $request->role_id;
        $user->password = $request->password ? Hash::make($request->password) : $user->password;
        $user->save();

        return redirect()->route('users.index')->with([
            'message' => 'User updated successfuly.',
            'class' => 'bg-success'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, string $id)
    {
        $user = User::findOrFail($id)
            ->delete();

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Success',
                'data' => $user
            ]);
        }

        return redirect()->route('users.index')->with([
            'message' => 'User delete successfuly.',
            'class' => 'bg-success'
        ]);
    }
}
