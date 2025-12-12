<?php

namespace App\View\Components\Widgets;

use App\Models\Post;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\Component;

class Popular extends Component
{
    public $posts;
    public bool $enabled;

    public function __construct()
    {
        $this->enabled = (bool) setting('sidebar_popular_enabled', true);
        
        if (!$this->enabled) {
            $this->posts = collect();
            return;
        }

        $ttl = config('cache.ttl', 3600);
        $limit = (int) setting('sidebar_popular_limit', 5);

        $this->posts = Cache::remember("sidebar.popular_posts.limit.{$limit}", $ttl, function () use ($limit) {
            return Post::published()->popular()->take($limit)->get();
        });
    }

    public function render(): View
    {
        return view('components.widgets.popular');
    }

    public function shouldRender(): bool
    {
        return $this->enabled && $this->posts->count() > 0;
    }
}

