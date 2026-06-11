<?php

namespace App\Http\Controllers;

use App\Models\ShortUrl;
use App\Models\UrlVisit;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Display the user's dashboard with their short URLs
     */
    public function index(): View
    {
        $userId = auth()->id();
        ShortUrl::expireStaleUrls($userId);
        $shortUrls = ShortUrl::activeShortUrls($userId);
        $expiredUrls = ShortUrl::getExpiredUrls($userId);

        return view(
            'dashboard',
            compact('shortUrls', 'expiredUrls')
        );
    }

    /**
     * Return visit counts for the authenticated user's URLs.
     */
    public function visitCounts(): JsonResponse
    {
        $counts = ShortUrl::where('user_id', auth()->id())
            ->withCount('visits')
            ->get(['id', 'visits_count'])
            ->mapWithKeys(fn (ShortUrl $url) => [$url->id => $url->visits_count]);

        return response()->json($counts);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request  The incoming request containing the original URL and optional timeout
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'original_url' => ['required', 'url', 'max:2048'],
            'timeout' => ['nullable', 'integer', 'in:0,1,24,168,720'],
        ]);

        ShortUrl::create([
            'user_id' => auth()->id(),
            'original_url' => $request->original_url,
            'short_code' => ShortUrl::generateUniqueShortCode(),
            'timeout' => $request->integer('timeout', 0),
        ]);

        return redirect()->route('dashboard')->with('success', 'Short URL created!');
    }

    /**
     * Redirect short code to original URL
     *
     * @param  string  $short_code  The short code url to redirect to original URL
     */
    public function redirect(string $short_code): RedirectResponse|View
    {
        $shortUrl = ShortUrl::where('short_code', $short_code)->firstOrFail();

        if ($shortUrl->isExpired()) {
            return view('expired');
        }

        UrlVisit::create(['short_url_id' => $shortUrl->id]);

        return redirect()->away($shortUrl->original_url);
    }
}
