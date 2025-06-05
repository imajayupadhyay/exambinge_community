<?php

namespace App\Http\Controllers;

use App\Models\Tweet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Inertia\Inertia;

class TweetController extends Controller
{
    /**
     * Show tweets with relationships and tag config
     */
    public function index()
    {
        $user = auth()->user();

        $tweets = Tweet::with(['user', 'likes'])
            ->latest()
            ->take(20)
            ->get()
            ->map(function ($tweet) use ($user) {
                return [
                    'id' => $tweet->id,
                    'content' => $tweet->content,
                    'exam_tag' => $tweet->exam_tag,
                    'created_at' => $tweet->created_at,
                    'likes_count' => $tweet->likes->count(),
                    'is_liked' => $tweet->likes->contains('user_id', $user->id),
                    'user' => [
                        'id' => $tweet->user->id,
                        'name' => $tweet->user->name,
                        'role' => $tweet->user->role,
                        'profile_photo_path' => $tweet->user->profile_photo_path,
                    ],
                ];
            });

        $examTags = Config::get('exam_tags');

        return Inertia::render('Tweets/Feed', [
            'tweets' => $tweets,
            'examTags' => $examTags,
        ]);
    }

    /**
     * Store new tweet
     */
    public function store(Request $request)
    {
        $request->validate([
            'content' => 'required|string|max:280',
            'exam_tags' => 'nullable|array',
            'exam_tags.*' => 'string|max:50',
        ]);

        $examTagString = is_array($request->exam_tags)
            ? implode(',', array_map('trim', $request->exam_tags))
            : null;

        $request->user()->tweets()->create([
            'content' => $request->content,
            'exam_tag' => $examTagString,
        ]);

        return redirect()->back();
    }

    /**
     * Toggle like/unlike
     */
    public function like(Request $request, Tweet $tweet)
    {
        $user = $request->user();

        $existingLike = $tweet->likes()->where('user_id', $user->id)->first();

        if ($existingLike) {
            $existingLike->delete();
        } else {
            $tweet->likes()->create(['user_id' => $user->id]);
        }

        return back();
    }
}
