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
    public function test_generate_is_valid_and_invalidate_method_should_work_as_expected(
        string $id,
        int $len,
        ?bool $should_invalidate = null,
        ?int $ttl = null
    ): void
    {
        $generated_at = now();
        $otp = $this->getOtp()->generate($id, $len);

        $this->assertEquals(
            $len,
            strlen($otp->getCode()),
            'Otp code digits does not match the expected length.'
        );

        $is_valid = $this->getOtp()->isValid($id, $otp->getCode(), $should_invalidate, $ttl);

        if (!is_null($ttl) && $generated_at->addSeconds($ttl)->lte(now())) {
            $this->assertFalse($is_valid, 'isValid should return false because of expiry');
        } else {
            $this->assertTrue(
                $is_valid,
                'isValid method does not work as expected.'
            );
        }

        if ($should_invalidate || is_null($should_invalidate)) {
            $by_default = is_null($should_invalidate) ? 'by default' : '';

            $this->assertFalse(
                $this->getOtp()->isValid($id, $otp->getCode()),
                "otp code should be invalidated $by_default after initial validation."
            );
        } else {
            $this->assertTrue(
                $this->getOtp()->isValid($id, $otp->getCode()),
                'otp code should remain valid after initial validation.'
            );
        }

        $this->getOtp()->invalidate($id);
        $this->assertFalse($this->getOtp()->isValid($id, $otp->getCode()), 'Invalidate method is not working as expected.');
    }

    public static function generateMethodDataProvider(): array
    {
        return [
            // 'must remain valid after verifying' => ['test@example.com', 10, false],
            // 'must invalidate after verifying' => ['test2@example.com', 4, true],
            // 'must invalidate after verifying (2)' => ['test2@example.com_app_user_password_reset', 4, true],
            // 'must invalidate by default' => ['test3@example.com', 6],
            'must be invalid due to expired otp' => ['2348105948744', 5, null, 0],
            'must be valid due to long ttl' => ['2348105948744', 5, null, 500],
        ];
    }

    protected function getOtp(array $config = []): Otp
    {
        $config = array_merge(config('otp'), $config);

        return new Otp($config);
    }
}
