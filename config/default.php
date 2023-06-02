<?php

return [
    // how long in seconds should the otp be valid for
    'ttl' => 300,

    // otp length
    'length' => 6,

    // set this to true if you want otp validation to always pass
    'always_pass' => false,

    // always invalidate otp after checking for it's validity
    'always_invalidate' => true,

    // used as salt for hashing operations
    'hashing_salt' => env('APP_KEY'),
];
