<?php

namespace app\Services\Otp\channels;

use app\Contracts\OtpChannel;
use Illuminate\Support\Facades\Mail;
use app\Mail\Otp\OtpCodeMail;

class EmailOtpChannel implements OtpChannel
{
    public function sendOtp(string $identifier, string $code): void
    {
        Mail::to($identifier)->send(new OtpCodeMail($code));
    }
}
