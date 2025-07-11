<?php

use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TweetController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\MyFeedController;
use App\Http\Controllers\FollowController;
use App\Http\Controllers\LeftSidebarController;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/
// Follow System Routes
Route::middleware(['auth', 'verified'])->group(function () {
    // Toggle follow/unfollow
    Route::post('/follow/toggle/{user}', [FollowController::class, 'toggle'])->name('follow.toggle');

    // View followers and followings
    Route::get('/users/{user}/followers', [FollowController::class, 'followers'])->name('user.followers');
    Route::get('/users/{user}/followings', [FollowController::class, 'followings'])->name('user.followings');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/followers', [FollowController::class, 'listFollowersPage'])->name('followers.page');
});


Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/myfeeds', [MyFeedController::class, 'index'])->name('myfeeds.index');
     Route::post('/follow/toggle/{user}', [FollowController::class, 'toggle'])->name('follow.toggle');
    Route::post('/myfeeds', [MyFeedController::class, 'store'])->name('myfeeds.store');
    Route::patch('/myfeeds/{tweet}', [MyFeedController::class, 'update'])->name('myfeeds.update');
    Route::delete('/myfeeds/{tweet}', [MyFeedController::class, 'destroy'])->name('myfeeds.destroy');
});

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

    Route::get('/left-sidebar-data', [LeftSidebarController::class, 'fetch'])->name('leftsidebar.fetch');



});

/*
|--------------------------------------------------------------------------
| Breeze Auth Routes
|--------------------------------------------------------------------------
*/

require __DIR__ . '/auth.php';
