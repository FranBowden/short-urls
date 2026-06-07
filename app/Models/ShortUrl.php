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
    ];

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
