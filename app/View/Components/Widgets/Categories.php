<?php

namespace App\View\Components\Widgets;

use App\Models\Category;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\Component;

class Categories extends Component
{
    public $categories;
    public bool $enabled;

    public function __construct()
    {
        $this->enabled = (bool) setting('sidebar_categories_enabled', true);
        
        if (!$this->enabled) {
            $this->categories = collect();
            return;
        }

        $ttl = config('cache.ttl', 3600);
        $limit = (int) setting('sidebar_categories_limit', 20);

        $this->categories = Cache::remember("sidebar.categories.top{$limit}", $ttl, function () use ($limit) {
            return Category::withCount('posts')
                ->having('posts_count', '>', 0)
                ->orderByDesc('posts_count')
                ->orderBy('name')
                ->take($limit)
                ->get();
        });
    }

    public function render(): View
    {
        return view('components.widgets.categories');
    }

    public function shouldRender(): bool
    {
        return $this->enabled && $this->categories->count() > 0;
    }
}

