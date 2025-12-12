<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class PageController extends Controller
{
    /**
     * Display a listing of pages.
     */
    public function index(Request $request): View
    {
        $query = Page::query();

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        $pages = $query->ordered()->paginate(15)->withQueryString();

        return view('admin.pages.index', compact('pages'));
    }

    /**
     * Show the form for creating a new page.
     */
    public function create(): View
    {
        return view('admin.pages.create');
    }

    /**
     * Store a newly created page.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:pages'],
            'content' => ['required', 'string'],
            'is_active' => ['boolean'],
            'order' => ['nullable', 'integer'],
            'metas' => ['nullable', 'array'],
        ]);

        $validated['slug'] = $validated['slug'] ?: Str::slug($validated['title']);
        $validated['is_active'] = $request->boolean('is_active', true);
        $validated['order'] = $validated['order'] ?? 0;

        $page = Page::create($validated);

        // Handle metas
        if (isset($validated['metas'])) {
            foreach ($validated['metas'] as $meta) {
                if (!empty($meta['key']) && isset($meta['value'])) {
                    $page->setMeta($meta['key'], $meta['value']);
                }
            }
        }

        return redirect()->route('admin.pages.index')
            ->with('success', __('admin.item_created', ['item' => __('admin.pages')]));
    }

    /**
     * Display the specified page.
     */
    public function show(Page $page): View
    {
        $page->load('metas');

        return view('admin.pages.show', compact('page'));
    }

    /**
     * Show the form for editing the specified page.
     */
    public function edit(Page $page): View
    {
        $page->load('metas');

        return view('admin.pages.edit', compact('page'));
    }

    /**
     * Update the specified page.
     */
    public function update(Request $request, Page $page): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:pages,slug,' . $page->id],
            'content' => ['required', 'string'],
            'is_active' => ['boolean'],
            'order' => ['nullable', 'integer'],
            'metas' => ['nullable', 'array'],
        ]);

        $validated['slug'] = $validated['slug'] ?: Str::slug($validated['title']);
        $validated['is_active'] = $request->boolean('is_active', true);

        $page->update($validated);

        // Handle metas
        if (isset($validated['metas'])) {
            $page->metas()->delete();
            foreach ($validated['metas'] as $meta) {
                if (!empty($meta['key']) && isset($meta['value'])) {
                    $page->setMeta($meta['key'], $meta['value']);
                }
            }
        }

        return redirect()->route('admin.pages.index')
            ->with('success', __('admin.item_updated', ['item' => __('admin.pages')]));
    }

    /**
     * Remove the specified page.
     */
    public function destroy(Page $page): RedirectResponse
    {
        $page->metas()->delete();
        $page->delete();

        return redirect()->route('admin.pages.index')
            ->with('success', __('admin.item_deleted', ['item' => __('admin.pages')]));
    }
}
