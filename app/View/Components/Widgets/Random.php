<?php

namespace App\View\Components\Widgets;

use App\Models\Post;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\Component;

class Random extends Component
{
    public $posts;
    public bool $enabled;

    public function __construct()
    {
        $this->enabled = (bool) setting('sidebar_random_enabled', true);
        
        if (!$this->enabled) {
            $this->posts = collect();
            return;
        }

        $ttl = config('cache.ttl', 3600);
        $limit = (int) setting('sidebar_random_limit', 5);

        // Cache a pool of 200 posts, then shuffle and take limit each request
        $pool = Cache::remember('sidebar.random_posts.pool', $ttl, function () {
            return Post::published()->latest('published_at')->take(200)->get();
        });

        // Shuffle the pool and take limit - this changes every request
        $this->posts = $pool->shuffle()->take($limit);
    }

    public function render(): View
    {
        return view('components.widgets.random');
    }

    public function shouldRender(): bool
    {
        return $this->enabled && $this->posts->count() > 0;
    }
}

