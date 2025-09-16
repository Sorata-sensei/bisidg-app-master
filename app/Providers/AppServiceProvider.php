<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Carbon\Carbon;
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
        $hour = Carbon::now()->hour;
        $greeting = 'Malam';

        if ($hour >= 5 && $hour < 12) {
            $greeting = 'Pagi';
        } elseif ($hour >= 12 && $hour < 15) {
            $greeting = 'Siang';
        } elseif ($hour >= 15 && $hour < 18) {
            $greeting = 'Sore';
        }
        view()->composer('*', function ($view) use ($greeting) {
            $view->with('pageTitle', session('pageTitle', 'Bisnis Digital'));
            $view->with('greeting', $greeting);
        });
    }
}