<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Page;
use App\Models\Post;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

class BlogController extends Controller
{
    /**
     * Get sidebar data with caching.
     */
    protected function getSidebarData(): array
    {
        $ttl = config('cache.ttl', 3600);

        $popularPosts = Cache::remember('sidebar.popular_posts', $ttl, function () {
            return Post::published()->popular()->take(5)->get();
        });

        $categories = Cache::remember('sidebar.categories', $ttl, function () {
            return Category::withCount('posts')
                ->having('posts_count', '>', 0)
                ->orderBy('name')
                ->get();
        });

        $tags = Cache::remember('sidebar.tags', $ttl, function () {
            return Tag::withCount('posts')
                ->having('posts_count', '>', 0)
                ->orderBy('name')
                ->get();
        });

        $pages = Cache::remember('navigation.pages', $ttl, function () {
            return Page::active()->ordered()->get();
        });

        return compact('popularPosts', 'categories', 'tags', 'pages');
    }

    /**
     * Display the blog homepage.
     */
    public function index(): View
    {
        $posts = Post::published()
            ->latest('published_at')
            ->with(['user', 'categories'])
            ->paginate(10);

        return view('blog.index', array_merge(['posts' => $posts], $this->getSidebarData()));
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

        $relatedPosts = Cache::remember("post.{$post->id}.related", config('cache.ttl', 3600), function () use ($post) {
            return Post::published()
                ->where('id', '!=', $post->id)
                ->whereHas('categories', function ($q) use ($post) {
                    $q->whereIn('categories.id', $post->categories->pluck('id'));
                })
                ->take(4)
                ->get();
        });

        return view('blog.post', array_merge(
            ['post' => $post, 'relatedPosts' => $relatedPosts],
            $this->getSidebarData()
        ));
    }

    /**
     * Display posts by category.
     */
    public function category(Category $category): View
    {
        $posts = Post::published()
            ->whereHas('categories', function ($q) use ($category) {
                $q->where('categories.id', $category->id);
            })
            ->latest('published_at')
            ->with(['user', 'categories'])
            ->paginate(10);

        return view('blog.category', array_merge(
            ['posts' => $posts, 'category' => $category],
            $this->getSidebarData()
        ));
    }

    /**
     * Display posts by tag.
     */
    public function tag(Tag $tag): View
    {
        $posts = Post::published()
            ->whereHas('tags', function ($q) use ($tag) {
                $q->where('tags.id', $tag->id);
            })
            ->latest('published_at')
            ->with(['user', 'categories'])
            ->paginate(10);

        return view('blog.tag', array_merge(
            ['posts' => $posts, 'tag' => $tag],
            $this->getSidebarData()
        ));
    }

    /**
     * Search posts.
     */
    public function search(Request $request): View
    {
        $query = $request->get('q', '');

        $posts = Post::published()
            ->where(function ($q) use ($query) {
                $q->where('title', 'like', "%{$query}%")
                  ->orWhere('content', 'like', "%{$query}%")
                  ->orWhere('excerpt', 'like', "%{$query}%");
            })
            ->latest('published_at')
            ->with(['user', 'categories'])
            ->paginate(10)
            ->withQueryString();

        return view('blog.search', array_merge(
            ['posts' => $posts, 'query' => $query],
            $this->getSidebarData()
        ));
    }
}
