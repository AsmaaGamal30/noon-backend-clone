<?php

namespace App\Contracts;

interface OtpChannel
{
    public function sendOtp(string $identifier, string $code): void;
}
