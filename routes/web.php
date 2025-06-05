<?php

use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TweetController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Http\Controllers\SearchController;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin'       => Route::has('login'),
        'canRegister'    => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion'     => PHP_VERSION,
    ]);
});
Route::get('/search', [SearchController::class, 'index'])->name('search');
/*
|--------------------------------------------------------------------------
| Google Auth Routes
|--------------------------------------------------------------------------
*/

Route::get('/auth/google', [GoogleController::class, 'redirect'])->name('google.redirect');
Route::get('/auth/google/callback', [GoogleController::class, 'callback']);

/*
|--------------------------------------------------------------------------
| Authenticated Routes (Protected)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'verified'])->group(function () {

    // Dashboard
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');

    // Onboarding Page
    Route::get('/onboarding', function () {
        return auth()->user()->onboarded
            ? redirect()->route('tweets.index')
            : Inertia::render('Onboarding');
    })->name('onboarding');

    // Save Preferences (Onboarding)
    Route::post('/save-preferences', function (Request $request) {
        $user = auth()->user();
        $user->preferences = implode(',', $request->preferences ?? []);
        $user->onboarded = true;
        $user->save();

        return redirect()->route('tweets.index');
    })->name('preferences.save');

    // Tweets
    Route::get('/tweets', [TweetController::class, 'index'])->name('tweets.index');
    Route::post('/tweets', [TweetController::class, 'store'])->name('tweets.store');
    Route::post('/tweets/{tweet}/like', [TweetController::class, 'like'])->name('tweets.like');
    Route::post('/tweets/{tweet}/reply', [TweetController::class, 'reply'])->name('tweets.reply');
Route::post('/tweets/{tweet}/retweet', [TweetController::class, 'retweet'])->name('tweets.retweet');


    // Profile Routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

/*
|--------------------------------------------------------------------------
| Breeze Auth Routes
|--------------------------------------------------------------------------
*/

require __DIR__ . '/auth.php';
