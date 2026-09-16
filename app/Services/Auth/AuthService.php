<?php

namespace App\Services\Auth;

use App\Models\User;
use App\Services\Otp\OtpChannelFactory;
use Illuminate\Support\Str;

class AuthService
{
    public function findOrCreateUser(string $identifier): User
    {
        $type = OtpChannelFactory::detectType($identifier);
        $column = $type === 'email' ? 'email' : 'phone';

        $user = User::firstOrCreate(
            [$column => $identifier],
            [
                'first_name' => 'customer',
                'password' => bcrypt(Str::random(32)),
            ]
        );
        return $user;
    }
}