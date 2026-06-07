<?php

namespace App\Http\Controllers;

use App\Models\ShortUrl;
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
        $shortUrls = ShortUrl::where('user_id', auth()->id())
            ->latest()
            ->get();

        return view('dashboard', compact('shortUrls'));
    }

    /**
     * Store a newly created resource in storage.
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
     */
    public function redirect($short_code): RedirectResponse
    {
        $shortUrl = ShortUrl::where('short_code', $short_code)->firstOrFail();

        if ($shortUrl->isExpired()) {
            abort(410, 'This short URL has expired.');
        }

        return redirect()->away($shortUrl->original_url);
    }
}
