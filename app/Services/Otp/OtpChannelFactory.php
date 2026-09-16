<?php

namespace App\Services\Otp;

use App\Contracts\OtpChannel;
use App\Services\Otp\Channels\EmailOtpChannel;
use App\Services\Otp\Channels\SmsOtpChannel;
use InvalidArgumentException;

class OtpChannelFactory
{
    public static function make(string $type): OtpChannel
    {
        return match ($type) {
            'email' => app(EmailOtpChannel::class),
            'phone' => app(SmsOtpChannel::class),
            default => throw new InvalidArgumentException("Unsupported OTP type: {$type}"),
        };
    }

    public static function detectType(string $identifier): string
    {
        return filter_var($identifier, FILTER_VALIDATE_EMAIL) ? 'email' : 'phone';
    }
}
