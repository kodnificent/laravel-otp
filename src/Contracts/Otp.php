<?php

namespace Kodnificent\LaravelOtp\Contracts;

interface Otp
{
    public function create(): self;

    public function send(): self;
}
