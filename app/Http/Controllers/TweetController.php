<?php

namespace App\Http\Controllers;

use App\Models\Tweet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Inertia\Inertia;

class TweetController extends Controller
{
    public function index()
    {
        $tweets = Tweet::with('user')->latest()->take(20)->get();
        $examTags = Config::get('exam_tags'); // 👈 from config/exam_tags.php

        return Inertia::render('Tweets/Feed', [
            'tweets' => $tweets,
            'examTags' => $examTags,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'content' => 'required|string|max:280',
            'exam_tags' => 'nullable|array',
            'exam_tags.*' => 'string|max:50',
        ]);

        $examTagString = is_array($request->exam_tags) ? implode(',', $request->exam_tags) : null;

        $request->user()->tweets()->create([
            'content' => $request->content,
            'exam_tag' => $examTagString, // stored as string
        ]);

        return back();
    }
}
