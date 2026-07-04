<?php

namespace App\Http\Controllers;

use App\Models\GuestUrl;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(Request $request): View
    {
        $sessionId = $request->session()->getId();
        $linksUsed = GuestUrl::countForSession($sessionId);
        $linksRemaining = max(0, GuestUrlController::GUEST_LIMIT - $linksUsed);

        return view('welcome', compact('linksUsed', 'linksRemaining'));
    }
}
