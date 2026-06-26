<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Carbon\Carbon;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use App\Models\Setting;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Carbon::setLocale('id');

        try {
            if (Schema::hasTable('settings')) {
                // Set timezone dynamically from database
                $timezone = Setting::get('timezone', config('app.timezone', 'Asia/Jakarta'));
                config(['app.timezone' => $timezone]);
                date_default_timezone_set($timezone);

                // Share hospital name globally with views
                $namaRS = Setting::get('nama_rumah_sakit', 'RSUD Andi Makkasau');
                View::share('nama_rumah_sakit', $namaRS);
            } else {
                View::share('nama_rumah_sakit', 'RSUD Andi Makkasau');
            }
        } catch (\Exception $e) {
            // Fallback if database connection is not ready
            View::share('nama_rumah_sakit', 'RSUD Andi Makkasau');
        }
    }
}

