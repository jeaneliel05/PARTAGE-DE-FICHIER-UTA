<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
        $this->middleware(function ($request, $next) {
            if ($request->user()->role !== 'admin') {
                return response()->json(['error' => 'Unauthorized'], 403);
            }
            return $next($request);
        })->only(['store', 'update', 'destroy']);
    }

    public function index()
    {
        return response()->json(User::all(), 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'username' => 'required|unique:users',
            'email' => 'required|unique:users|email',
            'password' => 'required|min:6',
            'role' => 'required|in:admin,student',
        ]);

        $user = User::create([
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        return response()->json($user, 201);
    }

    public function show($id)
    {
        $user = User::findOrFail($id);
        return response()->json($user, 200);
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'username' => 'required|unique:users,username,' . $id,
            'email' => 'required|unique:users,email,' . $id,
            'password' => 'required|min:6',
            'role' => 'required|in:admin,student',
        ]);

        $user->update([
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        return response()->json($user, 200);
    }

    public function destroy($id)
    {
        User::destroy($id);
        return response()->json(null, 204);
    }
}
