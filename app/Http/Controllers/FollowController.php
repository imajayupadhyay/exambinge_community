<?php

namespace App\Http\Controllers;

use App\Models\Follower;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class FollowController extends Controller
{
    /**
     * Toggle follow/unfollow for a user.
     */
    public function toggle(Request $request, User $user)
    {
        $authUser = Auth::user();

        // Prevent self-following
        if ($authUser->id === $user->id) {
            return response()->json(['error' => 'You cannot follow yourself.'], 403);
        }

        // Check if already following
        $existing = Follower::where('follower_id', $authUser->id)
                            ->where('followed_id', $user->id)
                            ->first();

        if ($existing) {
            $existing->delete();
            return response()->json(['status' => 'unfollowed']);
        } else {
            Follower::create([
                'follower_id' => $authUser->id,
                'followed_id' => $user->id,
            ]);
            return response()->json(['status' => 'followed']);
        }
    }

    /**
     * Get list of followers for a user.
     */
    public function followers(User $user)
    {
        $followers = $user->followers()
                          ->with('follower:id,name,profile_photo_path')
                          ->get()
                          ->pluck('follower');

        return response()->json($followers);
    }

    /**
     * Get list of followings for a user.
     */
    public function followings(User $user)
    {
        $followings = $user->followings()
                           ->with('followed:id,name,profile_photo_path')
                           ->get()
                           ->pluck('followed');

        return response()->json($followings);
    }
    

public function listFollowersPage()
{
    $authUser = Auth::user();

    // Fetch users they follow (followings)
    $followedUsers = $authUser->followings()
        ->with('followed:id,name,role,profile_photo_path')
        ->get()
        ->pluck('followed');

    return Inertia::render('Followers/Index', [
        'auth' => $authUser,
        'users' => $followedUsers,
    ]);
}



}
