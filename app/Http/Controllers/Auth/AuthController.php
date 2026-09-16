<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RequestCodeRequest;
use App\Http\Requests\Auth\VerifyCodeRequest;
use App\Services\Auth\AuthService;
use App\Services\Otp\OtpService;
use Illuminate\Http\JsonResponse;

class AuthController extends Controller
{
    public function __construct(
        private OtpService $otpService,
        private AuthService $authService,
    ) {}

    public function requestCode(RequestCodeRequest $request): JsonResponse
    {
        $this->otpService->requestCode($request->validated('identifier'));

        return response()->json(['message' => 'Code sent.']);
    }

    public function verifyCode(VerifyCodeRequest $request): JsonResponse
    {
        $this->otpService->verifyCode($request->validated('identifier'), $request->validated('code'));

        $user = $this->authService->findOrCreateUser($request->validated('identifier'));

        return response()->json([
            'user' => $user,
            'token' => $user->createToken('auth_token')->plainTextToken,
        ]);
    }
}
