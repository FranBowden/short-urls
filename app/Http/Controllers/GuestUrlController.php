<?php

namespace App\Http\Controllers;

use App\Models\GuestUrl;
use App\Models\ShortUrl;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class GuestUrlController extends Controller
{
    public const GUEST_LIMIT = 3;

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'original_url' => ['required', 'url', 'max:2048'],
        ]);

        $sessionId = $request->session()->getId();
        $linksUsed = GuestUrl::countForSession($sessionId);

        if ($linksUsed >= self::GUEST_LIMIT) {
            return response()->json([
                'error' => 'limit_reached',
                'message' => 'You\'ve used all '.self::GUEST_LIMIT.' free links. Sign up for unlimited!',
            ], 422);
        }

        $guestUrl = GuestUrl::create([
            'session_id' => $sessionId,
            'original_url' => $request->original_url,
            'short_code' => ShortUrl::generateUniqueShortCode(),
            'expires_at' => now()->addDays(30),
        ]);

        $linksUsed = GuestUrl::countForSession($sessionId);

        return response()->json([
            'short_url' => url($guestUrl->short_code),
            'links_used' => $linksUsed,
            'links_remaining' => self::GUEST_LIMIT - $linksUsed,
        ]);
    }
}
