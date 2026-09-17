<?php

namespace App\Services\Auth;

use App\Models\User;
use App\Services\Otp\OtpChannelFactory;
use Illuminate\Support\Str;

class AuthService
{
    public function findOrCreateUser(string $identifier): User
    {
        $identifier = OtpChannelFactory::normalizeIdentifier($identifier);
        $column = OtpChannelFactory::detectType($identifier)->value;

        return User::firstOrCreate(
            [$column => $identifier],
            [
                'first_name' => 'customer',
                'password' => bcrypt(Str::random(32)),
            ]
        );
    }
}
