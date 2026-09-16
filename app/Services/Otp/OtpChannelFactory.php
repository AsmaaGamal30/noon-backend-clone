<?php

namespace app\Services\Otp;

use app\Contracts\OtpChannel;
use app\Services\Otp\Channels\EmailOtpChannel;
use app\Services\Otp\Channels\SmsOtpChannel;
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
        $identifier = trim($identifier);
        $identifier = strtolower($identifier);
        return filter_var($identifier, FILTER_VALIDATE_EMAIL) ? 'email' : 'phone';
    }
}