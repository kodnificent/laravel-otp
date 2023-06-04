<?php

namespace Kodnificent\LaravelOtp\Facades;

use Illuminate\Support\Facades\Facade;
use Kodnificent\LaravelOtp\Contracts\Otp as OtpContract;

/**
 * @method static string generate(string $identifier, ?int $length = null)
 * @method static bool isValid(string $identifier, string $code, ?bool $invalidate = null, ?int $ttl = null)
 * @method static void invalidate(string $identifier)
 */
class Otp extends Facade
{
    protected static function getFacadeAccessor()
    {
        return OtpContract::class;
    }
}
