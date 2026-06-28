<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;

class AdministratorController extends Controller
{
    /**
     * Menyimpan administrator baru.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => [
                'required',
                'string',
                'max:255',
                'unique:users,username',
            ],
            'password' => [
                'required',
                'string',
                'min:6',
                'confirmed',
            ],
        ], [
            'username.required' => 'Username wajib diisi.',
            'username.unique' => 'Username sudah digunakan.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal 6 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak sesuai.',
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput()
                ->with('openModal', 'create-admin');
        }

        User::create([
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'role' => 'admin',
        ]);

        return redirect()
            ->route('superadmin.dashboard')
            ->with('success', 'Administrator berhasil ditambahkan.');
    }

    /**
     * Memperbarui data administrator.
     */
    public function update(Request $request, User $administrator)
    {
        abort_if($administrator->role !== 'admin', 404);

        $validated = $request->validate([
            'username' => [
                'required',
                'string',
                'max:255',
                Rule::unique('users', 'username')->ignore($administrator->id),
            ],

            'password' => [
                'nullable',
                'string',
                'min:6',
                'confirmed',
            ],
        ], [
            'username.required' => 'Username wajib diisi.',
            'username.unique' => 'Username sudah digunakan.',
            'password.min' => 'Password minimal 6 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak sesuai.',
        ]);

        $administrator->username = $validated['username'];

        if (!empty($validated['password'])) {
            $administrator->password = Hash::make($validated['password']);
        }

        $administrator->save();

        return redirect()
            ->route('superadmin.dashboard')
            ->with('success', 'Administrator berhasil diperbarui.');
    }

    /**
     * Menghapus administrator.
     */
    public function destroy(User $administrator)
    {
        abort_if($administrator->role !== 'admin', 404);

        $administrator->delete();

        return redirect()
            ->route('superadmin.dashboard')
            ->with('success', 'Administrator berhasil dihapus.');
    }
}