<?php

namespace App\Http\Controllers;

use App\Models\Tweet;
use App\Models\Reply;
use App\Models\Retweet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Inertia\Inertia;

class TweetController extends Controller
{
    /**
     * Display the main tweet feed with user, likes, replies, and retweets
     */
    

public function index(Request $request)
{
    $user = auth()->user();

    $tweets = Tweet::with(['user', 'likes', 'replies.user', 'retweets'])
        ->latest()
        ->paginate(10);

    // Transform once
    $tweets->getCollection()->transform(function ($tweet) use ($user) {
        return [
            'id' => $tweet->id,
            'content' => $tweet->content,
            'exam_tag' => $tweet->exam_tag,
            'created_at' => $tweet->created_at,
            'likes_count' => $tweet->likes->count(),
            'is_liked' => $tweet->likes->contains('user_id', $user->id),
            'replies' => $tweet->replies->map(fn($r) => [
                'id' => $r->id,
                'content' => $r->content,
                'user' => ['id' => $r->user->id, 'name' => $r->user->name]
            ]),
            'retweets' => $tweet->retweets->map(fn($rt) => [
                'id' => $rt->id,
                'user_id' => $rt->user_id
            ]),
            'user' => [
                'id' => $tweet->user->id,
                'name' => $tweet->user->name,
                'role' => $tweet->user->role,
                'profile_photo_path' => $tweet->user->profile_photo_path,
            ],
        ];
    });

    if ($request->wantsJson() || $request->ajax()) {
        return response()->json([
            'tweets' => $tweets->items(), // ✅ Already transformed
            'nextPage' => $tweets->nextPageUrl(),
        ]);
    }

    $examTags = Config::get('exam_tags');

    return Inertia::render('Tweets/Feed', [
        'tweets' => $tweets->items(),
        'nextPageUrl' => $tweets->nextPageUrl(),
        'examTags' => $examTags,
    ]);
}


    /**
     * Store a new tweet
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

    $tweet = $request->user()->tweets()->create([
        'content' => $request->content,
        'exam_tag' => $examTagString,
    ]);

    // Load relations
    $tweet->load(['user', 'replies.user', 'retweets', 'likes']);

    $user = $request->user();

    return response()->json([
        'success' => true,
        'tweet' => [
            'id' => $tweet->id,
            'content' => $tweet->content,
            'exam_tag' => $tweet->exam_tag,
            'created_at' => $tweet->created_at,
            'likes_count' => $tweet->likes->count(),
            'is_liked' => false,
            'replies' => [],
            'retweets' => [],
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'role' => $user->role,
                'profile_photo_path' => $user->profile_photo_path,
            ],
        ]
    ]);
}


    /**
     * Like or Unlike a tweet
     */
   public function like(Request $request, Tweet $tweet)
{
    $user = $request->user();
    $existingLike = $tweet->likes()->where('user_id', $user->id)->first();

    if ($existingLike) {
        $existingLike->delete();
    } else {
        $tweet->likes()->create([
            'user_id' => $user->id,
        ]);
    }

    $tweet->load('likes');

    return response()->json([
        'likes_count' => $tweet->likes->count(),
        'is_liked' => $tweet->likes->contains('user_id', $user->id),
    ]);
}


    /**
     * Submit a reply to a tweet
     */
    public function reply(Request $request, Tweet $tweet)
{
    $request->validate([
        'content' => 'required|string|max:280',
    ]);

    $reply = $tweet->replies()->create([
        'user_id' => $request->user()->id,
        'content' => $request->content,
    ]);

    $reply->load('user'); // load user for immediate Vue UI display

    return response()->json(['reply' => $reply]);
}


    /**
     * Retweet functionality
     */
    public function retweet(Request $request, Tweet $tweet)
{
    $user = $request->user();

    $alreadyRetweeted = $tweet->retweets()->where('user_id', $user->id)->exists();

    if ($alreadyRetweeted) {
        return response()->json(['message' => 'Already retweeted'], 409);
    }

    $tweet->retweets()->create([
        'user_id' => $user->id,
    ]);

    $newTweet = [
        'id' => $tweet->id,
        'content' => $tweet->content,
        'exam_tag' => $tweet->exam_tag,
        'created_at' => $tweet->created_at,
        'likes_count' => $tweet->likes->count(),
        'is_liked' => $tweet->likes->contains('user_id', $user->id),
        'replies' => $tweet->replies->map(function ($reply) {
            return [
                'id' => $reply->id,
                'content' => $reply->content,
                'user' => [
                    'id' => $reply->user->id,
                    'name' => $reply->user->name,
                ],
            ];
        }),
        'retweets' => $tweet->retweets->map(function ($retweet) {
            return [
                'id' => $retweet->id,
                'user_id' => $retweet->user_id,
            ];
        }),
        'user' => [
            'id' => $tweet->user->id,
            'name' => $tweet->user->name,
            'role' => $tweet->user->role,
            'profile_photo_path' => $tweet->user->profile_photo_path,
        ],
    ];

    return response()->json(['retweeted_tweet' => $newTweet]);
}

}
