<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

class PostController extends Controller
{
    /**
     * Display the blog homepage.
     */
    public function index(): View
    {
        $perPage = (int) setting('posts_per_page', 10);

        $posts = Post::published()
            ->latest('published_at')
            ->with(['user', 'categories', 'tags'])
            ->paginate($perPage);

        return view('home', ['posts' => $posts]);
    }

    /**
     * Display a single post.
     */
    public function show(Post $post): View
    {
        // Only show published posts to non-admins
        if ($post->status !== 'published' && (!auth()->check() || !auth()->user()->isAdmin())) {
            abort(404);
        }

        $post->incrementViews();
        $post->load(['user', 'categories', 'tags', 'metas']);

        $ttl = config('cache.ttl', 3600);
        $relatedLimit = (int) setting('related_posts_limit', 12);

        // Fetch related posts for inline display + bottom section
        $relatedPosts = Cache::remember("post.{$post->id}.related.v3", $ttl, function () use ($post, $relatedLimit) {
            return Post::published()
                ->where('id', '!=', $post->id)
                ->whereHas('categories', function ($q) use ($post) {
                    $q->whereIn('categories.id', $post->categories->pluck('id'));
                })
                ->inRandomOrder()
                ->take($relatedLimit)
                ->get();
        });

        return view('posts.show', [
            'post' => $post,
            'relatedPosts' => $relatedPosts,
        ]);
    }
}
