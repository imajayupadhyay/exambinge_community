<?php
namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Tweet;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->query('q');

        $users = User::where('name', 'like', "%{$q}%")
                     ->limit(5)
                     ->get(['id', 'name', 'role', 'profile_photo_path']);

        $tweets = Tweet::where('content', 'like', "%{$q}%")
                       ->limit(5)
                       ->with('user:id,name')
                       ->get();

        // Simulated exam tags for demo; replace with DB fetch if needed
        $examTags = collect(config('exam_tags', []))
                    ->filter(fn($tag) => stripos($tag, $q) !== false)
                    ->take(5)
                    ->values();

        return response()->json([
            'users' => $users,
            'tweets' => $tweets,
            'exams' => $examTags,
        ]);
    }
}
