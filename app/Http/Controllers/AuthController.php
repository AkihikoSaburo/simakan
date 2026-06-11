<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => ['required'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {

            $request->session()->regenerate();

            $user = Auth::user();

            return match ($user->role) {
                'superadmin' => redirect()->route('superadmin.dashboard'),
                'admin'      => redirect()->route('admin.dashboard'),
                'dapur'    => redirect()->route('dapur.dashboard'),
                'bangsal'    => redirect()->route('bangsal.dashboard'),
                default      => abort(403, 'Role tidak dikenali'),
            };
        }

        return back()->withErrors([
            'username' => 'Username atau password salah.'
        ])->onlyInput('username');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/login');
    }
}