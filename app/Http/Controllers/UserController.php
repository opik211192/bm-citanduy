<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('roles')->get();
        $roles = Role::all();

        return view('backend.users.index', compact('users', 'roles'));
    }

public function store(Request $request)
{
    $request->validate([
        'name'  => 'required|string|max:255',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|string|min:6|confirmed',
        'role'  => 'required|array',
        'role.*' => 'exists:roles,name',
    ]);

    $user = User::create([
        'name'     => $request->name,
        'username' => $request->username,
        'email'    => $request->email,
        'password' => Hash::make($request->password),
    ]);

    $user->syncRoles($request->role);

    return response()->json([
        'status'  => true,
        'message' => 'User berhasil ditambahkan.',
        'data'    => [
            'id'    => $user->id,
            'name'  => $user->name,
            'username' => $user->username,
            'email' => $user->email,
            'role'  => implode(', ', $user->getRoleNames()->toArray())
        ]
    ]);
}

public function update(Request $request, User $user)
{
    $request->validate([
        'name'  => 'required|string|max:255',
        'username' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email,' . $user->id,
        'role'  => 'required|array',
        'role.*' => 'exists:roles,name',
    ]);

    $user->update([
        'name'  => $request->name,
        'username' => $request->username,
        'email' => $request->email,
    ]);

    if ($request->filled('password')) {
        $user->update([
            'password' => Hash::make($request->password),
        ]);
    }

    $user->syncRoles($request->role);

    return response()->json([
        'status'  => true,
        'message' => 'User berhasil diperbarui.',
        'data'    => [
            'id'    => $user->id,
            'name'  => $user->name,
            'username' => $user->username,
            'email' => $user->email,
            'role'  => implode(', ', $user->getRoleNames()->toArray())
        ]
    ]);
}


    public function destroy(User $user)
    {
        $user->delete();

        return response()->json([
            'status'  => true,
            'message' => 'User berhasil dihapus.',
            'data'    => $user
        ]);
    }
}
