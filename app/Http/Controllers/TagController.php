<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

class TagController extends Controller
{
    /**
     * Display all tags archive.
     */
    public function index(Request $request): View
    {
        $ttl = config('cache.ttl', 3600);
        $sort = $request->get('sort', 'name');
        $page = $request->get('page', 1);

        $tags = Cache::remember("tags.archive.{$sort}.page.{$page}", $ttl, function () use ($sort) {
            $query = Tag::withCount('posts')->having('posts_count', '>', 0);
            
            if ($sort === 'posts') {
                $query->orderByDesc('posts_count')->orderBy('name');
            } else {
                $query->orderBy('name')->orderByDesc('posts_count');
            }
            
            return $query->paginate(200);
        });

        return view('tags.index', [
            'tags' => $tags,
            'sort' => $sort,
        ]);
    }

    /**
     * Display posts by tag.
     */
    public function show(Tag $tag): View
    {
        $perPage = (int) setting('posts_per_page', 10);

        $posts = Post::published()
            ->whereHas('tags', function ($q) use ($tag) {
                $q->where('tags.id', $tag->id);
            })
            ->latest('published_at')
            ->with(['user', 'categories'])
            ->paginate($perPage);

        return view('tags.show', [
            'posts' => $posts,
            'tag' => $tag,
        ]);
    }
}
