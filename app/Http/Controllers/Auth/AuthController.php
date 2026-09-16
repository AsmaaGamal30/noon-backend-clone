<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\Auth\AuthService;
use App\Services\Otp\OtpService;
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