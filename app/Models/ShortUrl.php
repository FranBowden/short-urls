<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class ShortUrl extends Model
{
    protected $table = 'url_history';

    protected $fillable = [
        'user_id',
        'original_url',
        'short_code',
        'timeout',
        'expired_at',
    ];

    protected $casts = [
        'expired_at' => 'datetime',
    ];
    

    /**
     * Generate a unique short code for the URL
     *
     * @return string
     * - To do: Create other options such as generating their own custom code,
     * allow the user to suggest the length of the code.
     */
    public static function generateUniqueShortCode(): string
    {
        do {
            $code = Str::random(6);
        } while (self::where('short_code', $code)->exists());

        return $code;
    }

    /**
     * Return a list of active Short URLs for the user
     *
     * @param  int  $userId  The ID of the user to retrieve active URLs for
     * @return Collection
     */
    public static function activeShortUrls(int $userId)
    {
        return self::where('user_id', $userId)
            ->where(function ($query) {
                $query->where('timeout', 0)
                    ->orWhereRaw("created_at + (timeout * INTERVAL '1 hour') > NOW()");
            })
            ->withCount('visits')
            ->latest()
            ->get();
    }

    /**
     * Returns true if the link has a timeout set and it has elapsed
     */
    public function isExpired(): bool
    {
        if ($this->timeout === 0) {
            return false;
        }

        if ($this->created_at->addHours($this->timeout)->isPast()) {
            if ($this->expired_at === null) {
                $this->update(['expired_at' => $this->created_at->addHours($this->timeout)]);
            }

            return true;
        }

        return false;
    }

    /**
     * Get all expired URLs for that user
     *
     * @param  int  $userId  The ID of the user to retrieve expired URLs for
     * @return Collection
     *
     * - To do: Handle limitation and only load a certain number of expired
     *   URLs at a time, or paginate results for better performance if there
     *   are many expired URLs
     */
    public static function getExpiredUrls(int $userId)
    {
        return self::where('user_id', $userId)
            ->where('timeout', '>', 0)
            ->whereRaw("created_at + (timeout * INTERVAL '1 hour') <= NOW()")
            ->withCount('visits')
            ->latest()
            ->get();
    }

    /**
     * Get the label for the expiry time
     *
     * @return string
     */
    public function expiryLabel(): string
    {
        return match ($this->timeout) {
            1 => '1 hour',
            24 => '1 day',
            168 => '1 week',
            720 => '1 month',
            default => 'No expiry',
        };
    }

    /**
     * Get the visits for the short URL
     */
    public function visits(): HasMany
    {
        return $this->hasMany(UrlVisit::class);
    }
}
