<?php

namespace App\Providers;

use App\Models\Rental;
use App\Observers\RentalObserver;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\URL;
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
        Rental::observe(RentalObserver::class);
        Gate::define('access-customer-features', function ($user) {
            return $user->role === 'user';
        });
        if (config('app.env') === 'production') {
            URL::forceScheme('https');
        }
    }
}
