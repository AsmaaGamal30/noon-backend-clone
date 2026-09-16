<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;

Route::post('/auth/request-code', [AuthController::class, 'requestCode']);
Route::post('/auth/verify-code', [AuthController::class, 'verifyCode']);
