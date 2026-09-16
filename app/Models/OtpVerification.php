<?php

namespace App\Models;

use App\ConnectionType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Guarded;

#[Guarded([])]
class OtpVerification extends Model
{
    protected $casts = [
        'expires_at' => 'datetime',
        'type' => ConnectionType::class,
    ];
}