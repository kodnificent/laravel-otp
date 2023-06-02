<?php

namespace Kodnificent\LaravelOtp\Contracts;

interface Otp
{
    public function generate(
        string $identifier,
        ?int $length = null
    ): GeneratedOtp;

    public function isValid(string $identifier, string $code, ?bool $invalidate = null, ?int $ttl = null): bool;
}
