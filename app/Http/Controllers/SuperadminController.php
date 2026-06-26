<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class SuperadminController extends Controller
{
    /**
     * Display the superadmin dashboard.
     */
    public function dashboard()
    {
        $adminsCount = User::where('role', 'admin')->count();
        $dapurCount = User::where('role', 'dapur')->count();
        $bangsalCount = User::where('role', 'bangsal')->count();

        // Get recent admins created
        $recentAdmins = User::where('role', 'admin')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('superadmin.dashboard', compact('adminsCount', 'dapurCount', 'bangsalCount', 'recentAdmins'));
    }

    /**
     * Display a listing of admin accounts.
     */
    public function index()
    {
        $admins = User::where('role', 'admin')
            ->orderBy('username', 'asc')
            ->get();

        return view('superadmin.admins.index', compact('admins'));
    }

    /**
     * Show the form for creating a new admin.
     */
    public function create()
    {
        return view('superadmin.admins.create');
    }

    /**
     * Store a newly created admin in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'username' => ['required', 'string', 'max:255', 'unique:users,username'],
            'password' => ['required', 'string', 'min:6'],
        ], [
            'username.required' => 'Username wajib diisi.',
            'username.unique' => 'Username sudah digunakan oleh akun lain.',
            'password.required' => 'Kata sandi wajib diisi.',
            'password.min' => 'Kata sandi minimal terdiri dari 6 karakter.',
        ]);

        User::create([
            'username' => $request->username,
            'role' => 'admin',
            'password' => Hash::make($request->password),
        ]);

        return redirect()
            ->route('superadmin.admins.index')
            ->with('success', 'Akun Admin berhasil dibuat.');
    }

    /**
     * Show the form for editing the specified admin.
     */
    public function edit(User $admin)
    {
        if ($admin->role !== 'admin') {
            abort(404);
        }

        return view('superadmin.admins.edit', compact('admin'));
    }

    /**
     * Update the specified admin in storage.
     */
    public function update(Request $request, User $admin)
    {
        if ($admin->role !== 'admin') {
            abort(404);
        }

        $request->validate([
            'username' => ['required', 'string', 'max:255', Rule::unique('users')->ignore($admin->id)],
            'password' => ['nullable', 'string', 'min:6'],
        ], [
            'username.required' => 'Username wajib diisi.',
            'username.unique' => 'Username sudah digunakan oleh akun lain.',
            'password.min' => 'Kata sandi minimal terdiri dari 6 karakter.',
        ]);

        $data = [
            'username' => $request->username,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $admin->update($data);

        return redirect()
            ->route('superadmin.admins.index')
            ->with('success', 'Akun Admin berhasil diperbarui.');
    }

    /**
     * Remove the specified admin from storage.
     */
    public function destroy(User $admin)
    {
        if ($admin->role !== 'admin') {
            abort(404);
        }

        $admin->delete();

        return redirect()
            ->route('superadmin.admins.index')
            ->with('success', 'Akun Admin berhasil dihapus.');
    }
}
