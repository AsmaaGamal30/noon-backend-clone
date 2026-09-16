<?php

namespace App\Http\Controllers\Auth;

use app\Http\Controllers\Controller;
use app\Services\Auth\AuthService;
use app\Services\Otp\OtpService;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function __construct(
        private OtpService $otpService,
        private AuthService $authService,
    ) {
    }

    public function requestCode(Request $request)
    {
        $request->validate(['identifier' => 'required|string']);

        $this->otpService->requestCode($request->identifier);

        return response()->json(['message' => 'Code sent.']);
    }

    public function verifyCode(Request $request)
    {
        $request->validate([
            'identifier' => 'required|string',
            'code' => 'required|digits:6',
        ]);

        $this->otpService->verifyCode($request->identifier, $request->code);

        $user = $this->authService->findOrCreateUser($request->identifier);

        return response()->json([
            'user' => $user,
            'token' => $user->createToken('auth_token')->plainTextToken,
        ]);
    }
}
