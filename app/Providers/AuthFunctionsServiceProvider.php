<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AuthFunctionsServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        require_once base_path() . '/app/Functions/AuthFunctions.php';
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
