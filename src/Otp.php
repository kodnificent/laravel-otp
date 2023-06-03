<?php

namespace Kodnificent\LaravelOtp;

use Kodnificent\LaravelOtp\Contracts\Otp as OtpContract;
use Kodnificent\LaravelOtp\Exceptions\OtpException;
use Kodnificent\LaravelOtp\Models\OtpSecret;
use OTPHP\TOTP;

class Otp implements OtpContract
{
    protected $period = 1;
    protected $length;
    protected $ttl;
    protected $always_pass;
    protected $always_invalidate;
    protected $hashing_salt;

    public function __construct(array $config)
    {
        $this->setLength($config['length'] ?? null);
        $this->setTtl($config['ttl'] ?? null);
        $this->setAlwaysInvalidate($config['always_invalidate'] ?? true);
        $this->setHashingSalt($config['hashing_salt'] ?? null);
        $this->always_pass = $config['always_pass'] ?? false;
    }

    protected function fetchSecret(string $identifier): OtpSecret
    {
        $secret = OtpSecret::firstOrCreate(['identifier' => hash('sha256', $identifier . "-salt-{$this->hashing_salt}")]);

        return $secret;
    }

    protected function setAlwaysInvalidate(bool $always_invalidate): void
    {
        $this->always_invalidate = $always_invalidate;
    }

    protected function setHashingSalt(?string $salt): void
    {
        if (!$salt) {
            throw new OtpException('Hashing salt is not configured.');
        }

        $this->hashing_salt = $salt;
    }

    protected function setLength(int $length): void
    {
        if (!$length || $length < 4) {
            throw new OtpException('Invalid length configured.');
        }

        $this->length = $length;
    }

    protected function setTtl(int $ttl): void
    {
        if (!$ttl || $ttl < 0) {
            throw new OtpException('Invalid ttl configured.');
        }

        $this->ttl = $ttl;
    }

    protected function createOtp(?string $secret_key = null): TOTP
    {
        return TOTP::create($secret_key, $this->period, 'sha1', $this->length);
    }

    protected function afterValidation(string $identifier): void
    {
        if ($this->always_invalidate) {
            $this->invalidate($identifier);
        }
    }

    public function generate(string $identifier, ?int $length = null): GeneratedOtp
    {
        $this->setLength($length ?: $this->length);

        $secret = $this->fetchSecret($identifier);

        $otp = $this->createOtp();

        $secret->secret_key = $otp->getSecret();
        $secret->at = now()->getTimestamp();
        $secret->save();

        return new GeneratedOtp($otp->at($secret->at));
    }

    public function invalidate(string $identifier): void
    {
        $secret = $this->fetchSecret($identifier);
        $secret->at = null;
        $secret->save();
    }

    public function isValid(string $identifier, string $code, bool $invalidate = null, ?int $ttl = null): bool
    {
        $this->setTtl($ttl ?: $this->ttl);
        $this->setAlwaysInvalidate($invalidate ?: $this->always_invalidate);

        if ($this->always_pass) {
            return true;
        }

        $secret = $this->fetchSecret($identifier);

        if (!$secret->secret_key) {
            return false;
        }

        // check for expiry
        $at = now()->createFromTimestamp($secret->at);

        if ($at->addSeconds($this->ttl)->lte(now())) {
            $this->afterValidation($identifier);

            return false;
        }

        $is_valid = $this->createOtp($secret->secret_key)->verify($code, $secret->at);

        if ($is_valid) {
            $this->afterValidation($identifier);
        }

        return $is_valid;
    }

    public function send(): void
    {
        //
    }
}
