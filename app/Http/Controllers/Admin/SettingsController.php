<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    /**
     * Show the system settings form.
     */
    public function edit()
    {
        $timezone = Setting::get('timezone', config('app.timezone', 'Asia/Jakarta'));
        $nama_rumah_sakit = Setting::get('nama_rumah_sakit', 'RSUD Andi Makkasau');

        // Common Indonesian timezones
        $timezones = [
            'Asia/Jakarta' => 'Asia/Jakarta (WIB - UTC+7)',
            'Asia/Makassar' => 'Asia/Makassar (WITA - UTC+8)',
            'Asia/Jayapura' => 'Asia/Jayapura (WIT - UTC+9)',
            'UTC' => 'UTC (Universal Coordinated Time)',
        ];

        return view('admin.settings.edit', compact('timezone', 'nama_rumah_sakit', 'timezones'));
    }

    /**
     * Update the system settings in database.
     */
    public function update(Request $request)
    {
        $request->validate([
            'nama_rumah_sakit' => ['required', 'string', 'max:255'],
            'timezone' => ['required', 'string', 'timezone'],
        ], [
            'nama_rumah_sakit.required' => 'Nama Rumah Sakit wajib diisi.',
            'timezone.required' => 'Zona waktu (timezone) wajib dipilih.',
            'timezone.timezone' => 'Zona waktu yang dipilih tidak valid.',
        ]);

        Setting::set('nama_rumah_sakit', $request->nama_rumah_sakit);
        Setting::set('timezone', $request->timezone);

        return redirect()
            ->route('admin.settings.edit')
            ->with('success', 'Pengaturan sistem berhasil disimpan.');
    }
}
