<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password], $request->remember)) {
            $request->session()->regenerate();
            $user = Auth::user();
            if ($user->isAdmin()) return redirect()->route('admin.dashboard');
            if ($user->isStaff()) return redirect()->route('staff.dashboard');
            return redirect()->route('home');
        }

        return back()->withErrors(['email' => 'Invalid credentials.'])->onlyInput('email');
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'username'  => 'required|unique:users',
            'email'     => 'required|email|unique:users',
            'password'  => 'required|min:6|confirmed',
            'first_name'=> 'nullable|string',
            'last_name' => 'nullable|string',
        ]);

        $user = User::create([
            'username'   => $request->username,
            'email'      => $request->email,
            'password'   => Hash::make($request->password),
            'first_name' => $request->first_name,
            'last_name'  => $request->last_name,
            'role_id'    => 3,
        ]);

        Auth::login($user);
        return redirect()->route('home');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}
