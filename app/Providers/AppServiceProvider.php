<?php

namespace App\Providers;

use App\Models\ipzkt;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

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
        try {
            $zktss = ipzkt::all();
            View::share('zktss', $zktss);
        } catch (\Exception $e) {
            View::share('zktss', collect());
        }

        // URL::forceScheme('https');
        // URL::forceScheme('http');
    }
}
