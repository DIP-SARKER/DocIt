<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Auth;

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
        Auth::resolved(function ($auth) {
            $guard = $auth->guard('web');

            if ($guard instanceof SessionGuard) {
                // 30 days in minutes
                $guard->setRememberDuration(43200);
            }
        });
    }
}
