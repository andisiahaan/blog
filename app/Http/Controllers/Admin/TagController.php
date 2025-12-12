<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tag;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class TagController extends Controller
{
    /**
     * Display a listing of tags.
     */
    public function index(Request $request): View
    {
        $query = Tag::withCount('posts');

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $tags = $query->orderBy('name')->paginate(15)->withQueryString();

        return view('admin.tags.index', compact('tags'));
    }

    /**
     * Show the form for creating a new tag.
     */
    public function create(): View
    {
        return view('admin.tags.create');
    }

    /**
     * Store a newly created tag.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:tags'],
            'description' => ['nullable', 'string'],
            'metas' => ['nullable', 'array'],
        ]);

        $validated['slug'] = $validated['slug'] ?: Str::slug($validated['name']);

        $tag = Tag::create($validated);

        // Handle metas
        if (isset($validated['metas'])) {
            foreach ($validated['metas'] as $meta) {
                if (!empty($meta['key']) && isset($meta['value'])) {
                    $tag->setMeta($meta['key'], $meta['value']);
                }
            }
        }

        return redirect()->route('admin.tags.index')
            ->with('success', __('admin.item_created', ['item' => __('admin.tags')]));
    }

    /**
     * Display the specified tag.
     */
    public function show(Tag $tag): View
    {
        $tag->load(['posts' => function ($q) {
            $q->latest()->take(10);
        }, 'metas']);

        return view('admin.tags.show', compact('tag'));
    }

    /**
     * Show the form for editing the specified tag.
     */
    public function edit(Tag $tag): View
    {
        $tag->load('metas');

        return view('admin.tags.edit', compact('tag'));
    }

    /**
     * Update the specified tag.
     */
    public function update(Request $request, Tag $tag): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:tags,slug,' . $tag->id],
            'description' => ['nullable', 'string'],
            'metas' => ['nullable', 'array'],
        ]);

        $validated['slug'] = $validated['slug'] ?: Str::slug($validated['name']);

        $tag->update($validated);

        // Handle metas
        if (isset($validated['metas'])) {
            $tag->metas()->delete();
            foreach ($validated['metas'] as $meta) {
                if (!empty($meta['key']) && isset($meta['value'])) {
                    $tag->setMeta($meta['key'], $meta['value']);
                }
            }
        }

        return redirect()->route('admin.tags.index')
            ->with('success', __('admin.item_updated', ['item' => __('admin.tags')]));
    }

    /**
     * Remove the specified tag.
     */
    public function destroy(Tag $tag): RedirectResponse
    {
        $tag->posts()->detach();
        $tag->metas()->delete();
        $tag->delete();

        return redirect()->route('admin.tags.index')
            ->with('success', __('admin.item_deleted', ['item' => __('admin.tags')]));
    }
}
