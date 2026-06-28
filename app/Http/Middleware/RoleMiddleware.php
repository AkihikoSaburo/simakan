<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // 1. Cek apakah pengguna sudah login
        if (!Auth::check()) {
            return redirect('/login');
        }

        // 2. Ambil data user aktif
        $user = Auth::user();

        // 3. Cek apakah role user saat ini ada di dalam daftar array $roles yang diizinkan
        if (in_array($user->role, $roles)) {
            return $next($request);
        }

        // Jika tidak punya hak akses, lempar ke halaman 403 Forbidden
        abort(403, 'Anda tidak memiliki hak akses untuk halaman ini.');
    }
}