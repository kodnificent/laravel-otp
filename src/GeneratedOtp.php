<?php

namespace Kodnificent\LaravelOtp;

use Kodnificent\LaravelOtp\Contracts\GeneratedOtp as ContractsGeneratedOtp;

class GeneratedOtp implements ContractsGeneratedOtp
{
    protected $code;

    public function __construct(string $code) {
        $this->code = $code;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function send(): void
    {
        //
    }
}
