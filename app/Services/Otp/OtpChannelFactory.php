<?php

namespace App\Services\Otp;

use App\Contracts\OtpChannel;
use App\Enums\ConnectionType;
use App\Services\Otp\Channels\EmailOtpChannel;
use App\Services\Otp\Channels\SmsOtpChannel;

class OtpChannelFactory
{
    public static function make(ConnectionType $type): OtpChannel
    {
        return match ($type) {
            ConnectionType::EMAIL => app(EmailOtpChannel::class),
            ConnectionType::PHONE => app(SmsOtpChannel::class),
        };
    }

    public static function detectType(string $identifier): ConnectionType
    {
        return filter_var(trim($identifier), FILTER_VALIDATE_EMAIL)
            ? ConnectionType::EMAIL
            : ConnectionType::PHONE;
    }

    /**
     * Normalize an identifier so the same email or phone always maps to the same record.
     */
    public static function normalizeIdentifier(string $identifier): string
    {
        $identifier = trim($identifier);

        return match (self::detectType($identifier)) {
            ConnectionType::EMAIL => strtolower($identifier),
            ConnectionType::PHONE => preg_replace('/[\s\-()]/', '', $identifier),
        };
    }
}
