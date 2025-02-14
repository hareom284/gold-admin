<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Otp extends Model
{
    protected $fillable = [
        'phone_or_email',
        'otp',
        'expire_at',
        'is_verified',
    ];
}
