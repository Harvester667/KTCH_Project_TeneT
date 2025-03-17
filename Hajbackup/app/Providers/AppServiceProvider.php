<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;

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
    public function boot(): void {

        Gate::define( "super", function( $user ) {

            return $user->admin == 2;
        });

        Gate::define( "admin", function( $user ) {

            return $user->admin == 1;
        });
    }
}