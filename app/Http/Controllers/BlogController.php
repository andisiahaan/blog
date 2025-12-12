<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Page;
use App\Models\Post;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BlogController extends Controller
{
    /**
     * Display the blog homepage.
     */
    public function index(): View
    {
        $posts = Post::published()
            ->latest('published_at')
            ->with(['user', 'categories'])
            ->paginate(10);

        $popularPosts = Post::published()
            ->popular()
            ->take(5)
            ->get();

        $categories = Category::withCount('posts')
            ->having('posts_count', '>', 0)
            ->orderBy('name')
            ->get();

        $tags = Tag::withCount('posts')
            ->having('posts_count', '>', 0)
            ->orderBy('name')
            ->get();

        $pages = Page::active()->ordered()->get();

        return view('blog.index', compact('posts', 'popularPosts', 'categories', 'tags', 'pages'));
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

        $relatedPosts = Post::published()
            ->where('id', '!=', $post->id)
            ->whereHas('categories', function ($q) use ($post) {
                $q->whereIn('categories.id', $post->categories->pluck('id'));
            })
            ->take(4)
            ->get();

        $popularPosts = Post::published()
            ->popular()
            ->take(5)
            ->get();

        $pages = Page::active()->ordered()->get();

        return view('blog.post', compact('post', 'relatedPosts', 'popularPosts', 'pages'));
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

        $popularPosts = Post::published()->popular()->take(5)->get();
        $categories = Category::withCount('posts')->having('posts_count', '>', 0)->orderBy('name')->get();
        $tags = Tag::withCount('posts')->having('posts_count', '>', 0)->orderBy('name')->get();
        $pages = Page::active()->ordered()->get();

        return view('blog.category', compact('posts', 'category', 'popularPosts', 'categories', 'tags', 'pages'));
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

        $popularPosts = Post::published()->popular()->take(5)->get();
        $categories = Category::withCount('posts')->having('posts_count', '>', 0)->orderBy('name')->get();
        $tags = Tag::withCount('posts')->having('posts_count', '>', 0)->orderBy('name')->get();
        $pages = Page::active()->ordered()->get();

        return view('blog.tag', compact('posts', 'tag', 'popularPosts', 'categories', 'tags', 'pages'));
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

        $popularPosts = Post::published()->popular()->take(5)->get();
        $categories = Category::withCount('posts')->having('posts_count', '>', 0)->orderBy('name')->get();
        $tags = Tag::withCount('posts')->having('posts_count', '>', 0)->orderBy('name')->get();
        $pages = Page::active()->ordered()->get();

        return view('blog.search', compact('posts', 'query', 'popularPosts', 'categories', 'tags', 'pages'));
    }
}
