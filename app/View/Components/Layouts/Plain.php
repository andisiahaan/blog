<?php

namespace App\View\Components\Layouts;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Plain extends Component
{
    public string $title;

    public function __construct(
        ?string $title = null,
    ) {
        $this->title = $title ?? config('app.name');
    }

    public function render(): View
    {
        return view('layouts.plain');
    }
}
