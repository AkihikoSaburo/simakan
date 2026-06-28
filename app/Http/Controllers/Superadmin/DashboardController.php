<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Models\User;

class DashboardController extends Controller
{
    /**
     * Display the superadmin dashboard.
     */
    public function dashboard()
    {
        $adminsCount = User::where('role', 'admin')->count();

        $admins = User::where('role', 'admin')
            ->latest()
            ->take(5)
            ->get();

        // Placeholder sementara
        $lastBackup = null;
        $errorCount = 0;

        return view('superadmin.dashboard', compact(
            'adminsCount',
            'admins',
            'lastBackup',
            'errorCount'
        ));
    }
}