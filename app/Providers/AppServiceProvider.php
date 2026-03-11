<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Enforce a consistent password policy everywhere Rules\Password::defaults() is used:
        // register, reset-password, change-password.
        Password::defaults(fn () =>
            Password::min(8)
                ->letters()
                ->mixedCase()
                ->numbers()
                ->uncompromised()
        );
    }
}
