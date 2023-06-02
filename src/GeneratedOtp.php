<?php

namespace Kodnificent\LaravelOtp;

use Kodnificent\LaravelOtp\Contracts\GeneratedOtp as ContractsGeneratedOtp;

class GeneratedOtp implements ContractsGeneratedOtp
{
    public function __construct(protected string $code) {}

    public function getCode(): string
    {
        return $this->code;
    }

    public function send(): void
    {
        //
    }
}
