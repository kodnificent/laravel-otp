<?php

namespace Kodnificent\LaravelOtp\Models\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Support\Facades\Crypt;

class Encrypted implements CastsAttributes
{
    public function get($model, $key, $value, $attributes)
    {
        if ($value === null) {
            return null;
        }

        return Crypt::decrypt($value);
    }

    public function set($model, $key, $value, $attributes)
    {
        if ($value === null) {
            return null;
        }

        return Crypt::encrypt($value);
    }
}
