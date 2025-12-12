<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;

class SitemapController extends Controller
{
    /**
     * Number of posts per sitemap page.
     */
    protected int $postsPerPage = 1000;

    /**
     * Cache TTL for sitemaps.
     */
    protected function getCacheTtl(): int
    {
        return config('cache.ttl', 3600);
    }

    /**
     * Generate main sitemap index.
     */
    public function index(): Response
    {
        $content = Cache::remember('sitemap.index', $this->getCacheTtl(), function () {
            $sitemaps = [];

            // Static sitemaps
            $sitemaps[] = [
                'loc' => route('sitemap.tags'),
                'lastmod' => Tag::max('updated_at') ?? now(),
            ];
            $sitemaps[] = [
                'loc' => route('sitemap.categories'),
                'lastmod' => Category::max('updated_at') ?? now(),
            ];

            // Paginated post sitemaps
            $totalPosts = Post::published()->count();
            $totalPages = (int) ceil($totalPosts / $this->postsPerPage);

            for ($page = 1; $page <= max(1, $totalPages); $page++) {
                $sitemaps[] = [
                    'loc' => route('sitemap.posts', ['page' => $page]),
                    'lastmod' => Post::published()->max('updated_at') ?? now(),
                ];
            }

            return view('sitemap.index', compact('sitemaps'))->render();
        });

        return response($content, 200)->header('Content-Type', 'application/xml');
    }

    /**
     * Generate tags sitemap.
     */
    public function tags(): Response
    {
        $content = Cache::remember('sitemap.tags', $this->getCacheTtl(), function () {
            $tags = Tag::withCount('posts')
                ->having('posts_count', '>', 0)
                ->get();

            return view('sitemap.tags', compact('tags'))->render();
        });

        return response($content, 200)->header('Content-Type', 'application/xml');
    }

    /**
     * Generate categories sitemap.
     */
    public function categories(): Response
    {
        $content = Cache::remember('sitemap.categories', $this->getCacheTtl(), function () {
            $categories = Category::withCount('posts')
                ->having('posts_count', '>', 0)
                ->get();

            return view('sitemap.categories', compact('categories'))->render();
        });

        return response($content, 200)->header('Content-Type', 'application/xml');
    }

    /**
     * Generate posts sitemap (paginated).
     */
    public function posts(int $page = 1): Response
    {
        $cacheKey = "sitemap.posts.{$page}";

        $content = Cache::remember($cacheKey, $this->getCacheTtl(), function () use ($page) {
            $posts = Post::published()
                ->select('id', 'slug', 'title', 'updated_at', 'featured_image')
                ->orderBy('published_at', 'desc')
                ->skip(($page - 1) * $this->postsPerPage)
                ->take($this->postsPerPage)
                ->get();

            if ($posts->isEmpty() && $page > 1) {
                abort(404);
            }

            return view('sitemap.posts', compact('posts'))->render();
        });

        return response($content, 200)->header('Content-Type', 'application/xml');
    }
}
