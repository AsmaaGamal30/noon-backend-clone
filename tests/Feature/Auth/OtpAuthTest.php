<?php

use App\Mail\Otp\OtpCodeMail;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;

uses(RefreshDatabase::class);

test('email identifiers are normalized so the same user receives a token', function () {
    Mail::fake();

    $this->postJson('/api/auth/request-code', ['identifier' => '  Jane@Example.COM '])
        ->assertOk();

    $sentCode = null;
    Mail::assertQueued(OtpCodeMail::class, function (OtpCodeMail $mail) use (&$sentCode) {
        $sentCode = $mail->code;

        return $mail->hasTo('jane@example.com');
    });

    $this->postJson('/api/auth/verify-code', ['identifier' => 'jane@example.com', 'code' => $sentCode])
        ->assertOk()
        ->assertJsonStructure(['user', 'token']);

    expect(User::where('email', 'jane@example.com')->count())->toBe(1);
});

test('an invalid code is rejected', function () {
    Mail::fake();

    $this->postJson('/api/auth/request-code', ['identifier' => 'jane@example.com'])->assertOk();

    $this->postJson('/api/auth/verify-code', ['identifier' => 'jane@example.com', 'code' => '000000'])
        ->assertUnprocessable()
        ->assertJsonValidationErrors('code');

    expect(User::count())->toBe(0);
});

test('verify code requires a six digit code', function () {
    $this->postJson('/api/auth/verify-code', ['identifier' => 'jane@example.com', 'code' => '12'])
        ->assertUnprocessable()
        ->assertJsonValidationErrors('code');
});
