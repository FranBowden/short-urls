<?php

namespace App\Http\Controllers;

use App\Models\ShortUrl;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $shortUrls = ShortUrl::where('user_id', auth()->id())
            ->latest()
            ->get();

        return view('dashboard', compact('shortUrls'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'original_url' => ['required', 'url', 'max:2048'],
        ]);

        ShortUrl::create([
            'user_id' => auth()->id(),
            'original_url' => $request->original_url,
            'short_code' => ShortUrl::generateUniqueShortCode(),
        ]);

        return redirect()->route('dashboard')->with('success', 'Short URL created!');
    }
}
