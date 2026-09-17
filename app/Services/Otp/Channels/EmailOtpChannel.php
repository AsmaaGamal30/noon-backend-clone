<?php

namespace App\Services\Otp\Channels;

use App\Contracts\OtpChannel;
use Illuminate\Support\Facades\Mail;
use App\Mail\Otp\OtpCodeMail;

class EmailOtpChannel implements OtpChannel
{
    public function sendOtp(string $identifier, string $code): void
    {
        Mail::to($identifier)->send(new OtpCodeMail($code));
    }
}
