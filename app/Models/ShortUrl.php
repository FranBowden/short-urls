<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class ShortUrl extends Model
{
    protected $table = 'url_history';

    protected $fillable = [
        'user_id',
        'original_url',
        'short_code',
        'timeout',
    ];

    /**
     * Returns true if the link has a timeout set and it has elapsed.
     */
    public function isExpired(): bool
    {
        if ($this->timeout === 0) {
            return false;
        }

        return $this->created_at->addHours($this->timeout)->isPast();
    }

    /**
     * Get the user that owns the short URL
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Generate a unique short code for the URL
     */
    public static function generateUniqueShortCode(): string
    {
        do {
            $code = Str::random(6);
        } while (self::where('short_code', $code)->exists());

        return $code;
    }
}
