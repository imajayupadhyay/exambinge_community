<?php

namespace App\Http\Controllers;

use App\Models\Tweet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class MyFeedController extends Controller
{
    use AuthorizesRequests;

    /**
     * Display a listing of the authenticated user's tweets.
     */
    public function index()
    {
        $tweets = Tweet::with(['user', 'likes', 'replies', 'retweets'])
            ->where('user_id', Auth::id())
            ->latest()
            ->paginate(10);

        // Add image and avatar URL to the output
        $tweets->getCollection()->transform(function ($tweet) {
            return [
                'id' => $tweet->id,
                'content' => $tweet->content,
                'image' => $tweet->image ? asset('storage/' . $tweet->image) : null,
                'created_at' => $tweet->created_at,
                'user' => [
                    'id' => $tweet->user->id,
                    'name' => $tweet->user->name,
                    'avatar' => $tweet->user->profile_photo_path
                        ? asset('storage/' . $tweet->user->profile_photo_path)
                        : '/images/default-avatar.png',
                ],
                // Optionally include likes/replies/retweets
            ];
        });

        return Inertia::render('MyFeeds/Index', [
            'tweets' => $tweets,
            'auth' => [
                'user' => Auth::user(),
            ],
        ]);
    }

    /**
     * Store a newly created tweet for the authenticated user.
     */
    public function store(Request $request)
    {
        $request->validate([
            'content' => 'required|string|max:10000',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp,gif|max:2048',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('tweets', 'public');
        }

        Tweet::create([
            'user_id' => Auth::id(),
            'content' => $request->content,
            'image' => $imagePath,
        ]);

        return redirect()->route('myfeeds.index')->with('success', 'Tweet created successfully.');
    }

    /**
     * Update the specified tweet if it belongs to the authenticated user.
     */
    public function update(Request $request, Tweet $tweet)
    {
        $this->authorize('update', $tweet);

        $request->validate([
            'content' => 'required|string|max:10000',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp,gif|max:2048',
        ]);

        $imagePath = $tweet->image;

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('tweets', 'public');
        }

        $tweet->update([
            'content' => $request->content,
            'image' => $imagePath,
        ]);

        return redirect()->route('myfeeds.index')->with('success', 'Tweet updated successfully.');
    }

    /**
     * Remove the specified tweet if it belongs to the authenticated user.
     */
    public function destroy(Tweet $tweet)
    {
        $this->authorize('delete', $tweet);

        $tweet->delete();

        return redirect()->route('myfeeds.index')->with('success', 'Tweet deleted successfully.');
    }
}
