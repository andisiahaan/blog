<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SearchController extends Controller
{
    /**
     * Search posts.
     */
    public function __invoke(Request $request): View
    {
        $query = $request->get('q', '');
        $perPage = (int) setting('posts_per_page', 10);

        $posts = Post::published()
            ->where(function ($q) use ($query) {
                $q->where('title', 'like', "%{$query}%")
                  ->orWhere('content', 'like', "%{$query}%")
                  ->orWhere('excerpt', 'like', "%{$query}%");
            })
            ->latest('published_at')
            ->with(['user', 'categories'])
            ->paginate($perPage)
            ->withQueryString();

        return view('search', [
            'posts' => $posts,
            'query' => $query,
        ]);
    }
}
