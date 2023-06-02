<?php

namespace Kodnificent\LaravelOtp;

use Illuminate\Support\ServiceProvider;

class OtpServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/default.php', 'otp'
        );
    }

    public function boot(): void
    {
        $this->publishes([
            __DIR__.'/../config/default.php' => config_path('otp.php'),
        ]);

        $this->loadMigrationsFrom([
            __DIR__.'/../database/migrations'
        ]);
    }
}
