<?php

namespace app\Services\Otp\Channels;

use App\Contracts\OtpChannel;
use Illuminate\Support\Facades\Http;

class SmsOtpChannel implements OtpChannel
{
    public function sendOtp(string $identifier, string $code): void
    {
        $baseUrl = config('services.sms_misr.base_url');
        $environment = config('services.sms_misr.environment');
        $username = config('services.sms_misr.username');
        $password = config('services.sms_misr.password');
        $senderId = config('services.sms_misr.sender_id');
        $templateToken = config('services.sms_misr.template_token');

        $url = "{$baseUrl}environment={$environment}&username={$username}&password={$password}&sender={$senderId}&mobile={$identifier}&template={$templateToken}&otp={$code}";

        $response = Http::post($url);
        if ($response->failed()) {
            throw new \Exception('Failed to send OTP via SMS.');
        }
        return;

    }
}
