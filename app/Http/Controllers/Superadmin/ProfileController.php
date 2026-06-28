<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    /**
     * Menampilkan form edit profil superadmin.
     */
    public function edit()
    {
        // Mengambil data superadmin yang sedang login saat ini
        $superadmin = auth()->user();
        return view('superadmin.profile', compact('superadmin'));
    }

    /**
     * Memproses pembaruan username dan password superadmin.
     */
    public function update(Request $request)
    {
        $superadmin = auth()->user();

        $validated = $request->validate([
            'username' => [
                'required',
                'string',
                'max:255',
                // Unik di tabel users, tapi hiraukan id superadmin ini sendiri
                Rule::unique('users', 'username')->ignore($superadmin->id),
            ],
            'current_password' => [
                'nullable',
                'required_with:password', // Wajib diisi jika ingin ganti password baru
                'string',
            ],
            'password' => [
                'nullable',
                'string',
                'min:6',
                'confirmed', // Wajib ada input password_confirmation
            ],
        ], [
            'username.required' => 'Username wajib diisi.',
            'username.unique' => 'Username sudah digunakan oleh akun lain.',
            'current_password.required_with' => 'Password saat ini wajib diisi untuk mengganti password baru.',
            'password.min' => 'Password baru minimal 6 karakter.',
            'password.confirmed' => 'Konfirmasi password baru tidak sesuai.',
        ]);

        // Validasi Keamanan: Cek apakah password lama yang dimasukkan sudah benar
        if ($request->filled('password')) {
            if (!Hash::check($request->current_password, $superadmin->password)) {
                return back()->withErrors(['current_password' => 'Password saat ini tidak sesuai dengan database.'])->withInput();
            }
            // Jika benar, ganti password lama dengan password baru yang di-hash
            $superadmin->password = Hash::make($validated['password']);
        }

        // Update username
        $superadmin->username = $validated['username'];
        $superadmin->save();

        return redirect()
            ->route('superadmin.profile.edit')
            ->with('success', 'Profil Anda berhasil diperbarui.');
    }
}