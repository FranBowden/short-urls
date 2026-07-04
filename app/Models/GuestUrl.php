<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GuestUrl extends Model
{
    protected $fillable = [
        'session_id',
        'original_url',
        'short_code',
        'expires_at',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
    ];

    public function isExpired(): bool
    {
        return $this->expires_at->isPast();
    }

    public static function countForSession(string $sessionId): int
    {
        return self::where('session_id', $sessionId)->count();
    }
}
