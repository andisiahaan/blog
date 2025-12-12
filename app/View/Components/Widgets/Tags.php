<?php

namespace App\View\Components\Widgets;

use App\Models\Tag;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\Component;

class Tags extends Component
{
    public $tags;
    public bool $enabled;

    public function __construct()
    {
        $this->enabled = (bool) setting('sidebar_tags_enabled', true);
        
        if (!$this->enabled) {
            $this->tags = collect();
            return;
        }

        $ttl = config('cache.ttl', 3600);
        $limit = (int) setting('sidebar_tags_limit', 20);

        $this->tags = Cache::remember("sidebar.tags.top{$limit}", $ttl, function () use ($limit) {
            return Tag::withCount('posts')
                ->having('posts_count', '>', 0)
                ->orderByDesc('posts_count')
                ->orderBy('name')
                ->take($limit)
                ->get();
        });
    }

    public function render(): View
    {
        return view('components.widgets.tags');
    }

    public function shouldRender(): bool
    {
        return $this->enabled && $this->tags->count() > 0;
    }
}

