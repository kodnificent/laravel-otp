<?php

namespace Kodnificent\LaravelOtp\Models;
use Illuminate\Database\Eloquent\Model;
use Kodnificent\LaravelOtp\Models\Casts\Encrypted;

class OtpSecret extends Model
{
    protected $table = 'kodnificent_laravel_otp_otp_secrets';

    protected $casts = [
        'secret_key' => Encrypted::class,
        'at' => Encrypted::class,
    ];

    protected $guarded = [];
}
