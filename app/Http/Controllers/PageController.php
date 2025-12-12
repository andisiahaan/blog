<?php

namespace App\Http\Controllers;

use App\Models\Page;
use Illuminate\View\View;

class PageController extends Controller
{
    /**
     * Display a static page.
     */
    public function show(Page $page): View
    {
        // Only show active pages to non-admins
        if (!$page->is_active && (!auth()->check() || !auth()->user()->isAdmin())) {
            abort(404);
        }

        $page->load('metas');
        $pages = Page::active()->ordered()->get();

        return view('pages.show', compact('page', 'pages'));
    }
}
