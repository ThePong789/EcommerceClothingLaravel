<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{User, Role};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::with('role');
        if ($request->role) $query->where('role_id', $request->role);
        if ($request->search) $query->where(function($q) use ($request) {
            $q->where('username', 'like', '%'.$request->search.'%')
              ->orWhere('email', 'like', '%'.$request->search.'%');
        });
        $users = $query->latest()->paginate(15)->withQueryString();
        $roles = Role::all();
        return view('admin.users.index', compact('users', 'roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'username' => 'required|unique:users',
            'email'    => 'required|email|unique:users',
            'password' => 'required|min:6',
            'role_id'  => 'required|exists:roles,role_id',
        ]);
        User::create(['username' => $request->username, 'email' => $request->email, 'password' => Hash::make($request->password), 'role_id' => $request->role_id]);
        return back()->with('success', 'User created!');
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $user->update($request->only('role_id', 'first_name', 'last_name'));
        return back()->with('success', 'User updated.');
    }

    public function destroy($id)
    {
        User::findOrFail($id)->delete();
        return back()->with('success', 'User deleted.');
    }
}
