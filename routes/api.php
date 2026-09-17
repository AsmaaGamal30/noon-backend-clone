<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;

Route::post('/auth/request-code', [AuthController::class, 'requestCode'])->name('auth.request-code');
Route::post('/auth/verify-code', [AuthController::class, 'verifyCode'])->name('auth.verify-code');