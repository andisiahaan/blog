<?php

namespace App\View\Components\Layouts;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Main extends Component
{
    public function __construct(
        public ?string $title = null,
        public ?string $description = null,
        public ?string $keywords = null,
        public ?string $ogTitle = null,
        public ?string $ogDescription = null,
        public ?string $ogType = 'website',
        public ?string $ogImage = null,
        public ?string $canonical = null,
    ) {
        $this->title = $title ?? setting('site_name', config('app.name'));
        $this->description = $description ?? setting('site_description', 'A modern blog');
        $this->ogTitle = $ogTitle ?? $this->title;
        $this->ogDescription = $ogDescription ?? $this->description;
    }

    public function render(): View
    {
        return view('layouts.main');
    }
}
