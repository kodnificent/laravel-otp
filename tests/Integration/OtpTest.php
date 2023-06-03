<?php

namespace Kodnificent\LaravelOtp\Tests\Integration;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Kodnificent\LaravelOtp\Otp;
use Kodnificent\LaravelOtp\Tests\TestCase;

class OtpTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @dataProvider generateMethodDataProvider
     */
    public function test_generate_method_should_create_pending_otp(string $id, int $len): void
    {
        $otp = $this->getOtp()->generate($id, $len);
        $this->assertEquals($len, strlen($otp->getCode()));

        $otp = $this->getOtp(['length' => 4])->generate('default_config_length@example.com');
        $this->assertEquals(4, strlen($otp->getCode()));
    }

    public static function generateMethodDataProvider(): array
    {
        return [
            ['test@example.com', 10],
            ['test2@example.com', 4],
            ['test3@example.com', 6]
        ];
    }

    protected function getOtp(array $config = []): Otp
    {
        $config = array_merge(config('otp'), $config);

        return new Otp($config);
    }
}
