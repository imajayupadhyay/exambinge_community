<?php

namespace App\Http\Controllers;

use App\Models\Tweet;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class LeftSidebarController extends Controller
{
    public function fetch()
    {
        $user = Auth::user();

        // Preferences (string to array)
        $preferences = explode(',', $user->preferences ?? '');

        // Suggested posts (latest, excluding user's own)
        $suggestedPosts = Tweet::with('user')
            ->where('user_id', '!=', $user->id)
            ->latest()
            ->take(3)
            ->get()
            ->map(function ($tweet) {
                return [
                    'id' => $tweet->id,
                    'content' => str()->limit($tweet->content, 100),
                    'user' => [
                        'id' => $tweet->user->id,
                        'name' => $tweet->user->name,
                        'profile_photo_path' => $tweet->user->profile_photo_path,
                    ],
                ];
            });

        // Suggested users (not followed by current user)
        $followingIds = $user->followings()->pluck('followed_id')->toArray();


        $suggestedUsers = User::where('id', '!=', $user->id)
            ->whereNotIn('id', $followingIds)
            ->inRandomOrder()
            ->take(5)
            ->get(['id', 'name', 'profile_photo_path']);

        return response()->json([
            'preferences' => $preferences,
            'suggestedPosts' => $suggestedPosts,
            'suggestedUsers' => $suggestedUsers,
        ]);
    }
}
