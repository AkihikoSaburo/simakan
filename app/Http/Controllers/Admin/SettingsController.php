<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage; // Impor Storage Facade

class SettingsController extends Controller
{
    /**
     * Show the system settings form.
     */
    public function edit()
    {
        $timezone = Setting::get('timezone', config('app.timezone', 'Asia/Jakarta'));
        $nama_rumah_sakit = Setting::get('nama_rumah_sakit', 'RSUD Andi Makkasau');
        $bg_login = Setting::get('bg_login'); // Ambil path gambar latar belakang login

        // Common Indonesian timezones
        $timezones = [
            'Asia/Jakarta' => 'Asia/Jakarta (WIB - UTC+7)',
            'Asia/Makassar' => 'Asia/Makassar (WITA - UTC+8)',
            'Asia/Jayapura' => 'Asia/Jayapura (WIT - UTC+9)',
            'UTC' => 'UTC (Universal Coordinated Time)',
        ];

        return view('admin.settings.edit', compact('timezone', 'nama_rumah_sakit', 'timezones', 'bg_login'));
    }

    /**
     * Update the system settings in database.
     */
    public function update(Request $request)
    {
        $request->validate([
            'nama_rumah_sakit' => ['required', 'string', 'max:255'],
            'timezone' => ['required', 'string', 'timezone'],
            'bg_login' => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:5120'], // Ubah ke 5120 (5MB)
        ], [
            'nama_rumah_sakit.required' => 'Nama Rumah Sakit wajib diisi.',
            'timezone.required' => 'Zona waktu (timezone) wajib dipilih.',
            'timezone.timezone' => 'Zona waktu yang dipilih tidak valid.',
            'bg_login.image' => 'Berkas harus berupa gambar.',
            'bg_login.mimes' => 'Format gambar harus jpeg, png, atau jpg.',
            'bg_login.max' => 'Ukuran gambar maksimal adalah 5MB.', // Update pesan error
        ]);

        // Simpan data teks biasa
        Setting::set('nama_rumah_sakit', $request->nama_rumah_sakit);
        Setting::set('timezone', $request->timezone);

        // Logika upload file bg_login
        if ($request->hasFile('bg_login')) {
            $oldPath = Setting::get('bg_login');

            // Hapus file lama di storage jika ada untuk menghemat kapasitas
            if ($oldPath && Storage::disk('public')->exists($oldPath)) {
                Storage::disk('public')->delete($oldPath);
            }

            // Simpan gambar baru ke folder storage/app/public/settings
            $path = $request->file('bg_login')->store('settings', 'public');

            // Simpan path relatifnya ke database key bg_login
            Setting::set('bg_login', $path);
        }

        return redirect()
            ->route('admin.settings.edit')
            ->with('success', 'Pengaturan sistem berhasil disimpan.');
    }
}