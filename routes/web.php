<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GuestUrlController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return Auth::check()
        ? redirect()->route('dashboard')
        : app(HomeController::class)->index(request());
})->name('home');

Route::post('/shorten', [GuestUrlController::class, 'store'])->name('guest.shorten')->middleware('guest');

Route::get('/{short_code}', [DashboardController::class, 'redirect'])->where('short_code', '[A-Za-z0-9]{6}');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/dashboard', [DashboardController::class, 'store'])->name('dashboard.store');
    Route::get('/dashboard/visit-counts', [DashboardController::class, 'visitCounts'])->name('dashboard.visit-counts');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
