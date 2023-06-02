<?php

namespace Kodnificent\LaravelOtp\Models;
use Illuminate\Database\Eloquent\Model;

class OtpSecret extends Model
{
    protected $table = 'kodnificent_laravel_otp_otp_secrets';

    protected $casts = [
        'secret_key' => 'encrypted',
        'at' => 'encrypted',
    ];

    protected $guarded = [];
}
