<?php

namespace Kodnificent\LaravelOtp\Contracts;

interface GeneratedOtp
{
    public function getCode(): ?string;

    public function send(): void;
}
