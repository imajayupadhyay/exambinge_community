<?php

namespace App\Http\Controllers;

use App\Models\Tweet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class MyFeedController extends Controller
{
    /**
     * Display a listing of the authenticated user's tweets.
     */
    use AuthorizesRequests;
    public function index()
    {
        $tweets = Tweet::with(['user', 'likes', 'replies', 'retweets']) // ✅ ensure 'user' is loaded
            ->where('user_id', Auth::id())
            ->latest()
            ->paginate(10);

        return Inertia::render('MyFeeds/Index', [
    'tweets' => $tweets,
    'auth' => [
        'user' => Auth::user(), // ✅ must wrap inside 'user'
    ],
]);

    }

    /**
     * Store a newly created tweet for the authenticated user.
     */
    public function store(Request $request)
    {
        $request->validate([
            'content' => 'required|string|max:280',
        ]);

        Tweet::create([
            'user_id' => Auth::id(),
            'content' => $request->content,
        ]);

        return redirect()->route('myfeeds.index')->with('success', 'Tweet created successfully.');
    }

    /**
     * Update the specified tweet if it belongs to the authenticated user.
     */
    public function update(Request $request, Tweet $tweet)
    {
        $this->authorize('update', $tweet); // uses Laravel Policy

        $request->validate([
            'content' => 'required|string|max:280',
        ]);

        $tweet->update([
            'content' => $request->content,
        ]);

        return redirect()->route('myfeeds.index')->with('success', 'Tweet updated successfully.');
    }

    /**
     * Remove the specified tweet if it belongs to the authenticated user.
     */
    public function destroy(Tweet $tweet)
    {
        $this->authorize('delete', $tweet); // uses Laravel Policy

        $tweet->delete();

        return redirect()->route('myfeeds.index')->with('success', 'Tweet deleted successfully.');
    }
}
