<?php

namespace App\Http\Controllers;

use App\Models\Tweet;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TweetController extends Controller
{
    public function index()
    {
        $tweets = Tweet::with('user')
            ->latest()
            ->take(20)
            ->get();

        return Inertia::render('Tweets/Feed', [
            'tweets' => $tweets,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'content' => 'required|string|max:280',
            'exam_tag' => 'nullable|string|max:50',
        ]);

        $request->user()->tweets()->create([
            'content' => $request->content,
            'exam_tag' => $request->exam_tag,
        ]);

        return redirect()->back();
    }
}
