<?php

namespace Kodnificent\LaravelOtp;

use Illuminate\Support\ServiceProvider;
use Kodnificent\LaravelOtp\Contracts\Otp as ContractsOtp;

class OtpServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/default.php', 'otp'
        );

        $this->app->bind(ContractsOtp::class, function ($app) {
            return new Otp($app->make('config')->get('otp'));
        });
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
