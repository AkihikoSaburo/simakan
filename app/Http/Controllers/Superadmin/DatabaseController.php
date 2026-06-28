<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Process\Process;
use Exception;

class DatabaseController extends Controller
{
    public function backup()
    {
        // 1. Tentukan nama file backup (contoh: backup-simakan-2026-06-28.sql)
        $filename = "backup-simakan-" . now()->format('Y-m-d_H-i-s') . ".sql";

        // 2. Ambil konfigurasi database dari file .env otomatis
        $dbName = config('database.connections.mysql.database');
        $dbUser = config('database.connections.mysql.username');
        $dbPass = config('database.connections.mysql.password');
        $dbHost = config('database.connections.mysql.host');

        // 3. Tentukan folder penyimpanan di storage/app/backups
        $storagePath = storage_path('app/backups');
        if (!file_exists($storagePath)) {
            mkdir($storagePath, 0755, true);
        }

        $filePath = $storagePath . '/' . $filename;

        try {
            // 4. Perintah mysqldump untuk backup database
            // Jika di Windows (XAMPP), pastikan path 'mysqldump' terdaftar di Environment Variables, atau tulis path lengkapnya
            $command = sprintf(
                'mysqldump --host=%s --user=%s --password=%s %s > %s',
                escapeshellarg($dbHost),
                escapeshellarg($dbUser),
                escapeshellarg($dbPass),
                escapeshellarg($dbName),
                escapeshellarg($filePath)
            );

            // Eksekusi perintah shell
            exec($command, $output, $returnVar);

            // Jika returnVar bukan 0, berarti mysqldump gagal/error
            if ($returnVar !== 0) {
                throw new Exception('Gagal mengeksekusi perintah mysqldump. Periksa konfigurasi database Anda.');
            }

            // 5. Download file langsung ke browser user, dan hapus file di server setelah didownload
            return response()->download($filePath)->deleteFileAfterSend(true);

        } catch (Exception $e) {
            return back()->with('error', 'Gagal melakukan backup database: ' . $e->getMessage());
        }
    }

    public function restore(Request $request)
    {
        // 1. Validasi input file dan teks konfirmasi
        $request->validate([
            'backup_file' => 'required|file',
            'confirmation' => 'required|string',
        ], [
            'backup_file.required' => 'File database wajib diunggah.',
            'confirmation.required' => 'Teks konfirmasi wajib diisi.',
        ]);

        // Kebijakan keamanan tambahan: pastikan teks konfirmasi pas
        if ($request->confirmation !== 'PULIHKAN DATABASE') {
            return back()->with('error', 'Gagal: Teks konfirmasi yang Anda masukkan salah.');
        }

        $file = $request->file('backup_file');

        // Pastikan ekensinya .sql
        if ($file->getClientOriginalExtension() !== 'sql') {
            return back()->with('error', 'Gagal: File harus berformat .sql');
        }

        // 2. Ambil konfigurasi database
        $dbName = config('database.connections.mysql.database');
        $dbUser = config('database.connections.mysql.username');
        $dbPass = config('database.connections.mysql.password');
        $dbHost = config('database.connections.mysql.host');

        $filePath = $file->getRealPath();

        try {
            // 3. Matikan foreign key checks sementara agar proses restore tidak terhambat relasi antar tabel
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');

            // 4. Jalankan perintah mysql lewat shell untuk import file .sql
            $command = sprintf(
                'mysql --host=%s --user=%s --password=%s %s < %s',
                escapeshellarg($dbHost),
                escapeshellarg($dbUser),
                escapeshellarg($dbPass),
                escapeshellarg($dbName),
                escapeshellarg($filePath)
            );

            exec($command, $output, $returnVar);

            // Hidupkan kembali foreign key checks
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');

            if ($returnVar !== 0) {
                throw new \Exception('Terjadi kesalahan saat mengeksekusi import database mysql.');
            }

            return redirect()
                ->route('superadmin.dashboard')
                ->with('success', 'Database berhasil dipulihkan (Restore) ke kondisi semula.');

        } catch (\Exception $e) {
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
            return back()->with('error', 'Gagal memulihkan database: ' . $e->getMessage());
        }
    }
}