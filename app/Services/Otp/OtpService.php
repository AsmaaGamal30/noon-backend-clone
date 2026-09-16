<?php

namespace app\Services\Otp;

use app\Models\OtpVerification;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;

class OtpService
{
    private const EXPIRES_IN_MINUTES = 5;
    private const MAX_ATTEMPTS = 5;

    public function requestCode(string $identifier)
    {
        $type = OtpChannelFactory::detectType($identifier);
        $key = "otp-request:{$identifier}";

        if (RateLimiter::tooManyAttempts($key, 1)) {
            $seconds = RateLimiter::availableIn($key);
            throw ValidationException::withMessages([
                'identifier' => "Please wait {$seconds}s before requesting another code.",
            ]);
        }
        RateLimiter::hit($key, 60);

        $code = (string) random_int(100000, 999999);

        OtpVerification::updateOrCreate(
            ['identifier' => $identifier, 'type' => $type],
            [
                'code' => Hash::make($code),
                'attempts' => 0,
                'expires_at' => now()->addMinutes(self::EXPIRES_IN_MINUTES),
            ]
        );

        OtpChannelFactory::make($type)->sendOtp($identifier, $code);
    }

    public function verifyCode(string $identifier, string $code)
    {
        $type = OtpChannelFactory::detectType($identifier);

        $otp = OtpVerification::where('identifier', $identifier)
            ->where('type', $type)
            ->first();

        if (!$otp || $otp->expires_at->isPast()) {
            throw ValidationException::withMessages(['code' => 'Code expired or not found.']);
        }

        if ($otp->attempts >= self::MAX_ATTEMPTS) {
            throw ValidationException::withMessages(['code' => 'Too many attempts, request a new code.']);
        }

        if (!Hash::check($code, $otp->code)) {
            $otp->increment('attempts');
            throw ValidationException::withMessages(['code' => 'Invalid code.']);
        }

        $otp->delete();
    }
}
