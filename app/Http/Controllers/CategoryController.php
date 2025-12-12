<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

class CategoryController extends Controller
{
    /**
     * Display all categories archive.
     */
    public function index(Request $request): View
    {
        $ttl = config('cache.ttl', 3600);
        $sort = $request->get('sort', 'name');
        $page = $request->get('page', 1);

        $categories = Cache::remember("categories.archive.{$sort}.page.{$page}", $ttl, function () use ($sort) {
            $query = Category::withCount('posts')->having('posts_count', '>', 0);
            
            if ($sort === 'posts') {
                $query->orderByDesc('posts_count')->orderBy('name');
            } else {
                $query->orderBy('name')->orderByDesc('posts_count');
            }
            
            return $query->paginate(200);
        });

        return view('categories.index', [
            'categories' => $categories,
            'sort' => $sort,
        ]);
    }

    /**
     * Display posts by category.
     */
    public function show(Category $category): View
    {
        $perPage = (int) setting('posts_per_page', 10);

        $posts = Post::published()
            ->whereHas('categories', function ($q) use ($category) {
                $q->where('categories.id', $category->id);
            })
            ->latest('published_at')
            ->with(['user', 'categories', 'tags'])
            ->paginate($perPage);

        return view('categories.show', [
            'posts' => $posts,
            'category' => $category,
        ]);
    }
}
