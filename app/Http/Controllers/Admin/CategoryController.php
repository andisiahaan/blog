<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class CategoryController extends Controller
{
    /**
     * Display a listing of categories.
     */
    public function index(Request $request): View
    {
        $query = Category::withCount('posts');

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $categories = $query->orderBy('name')->paginate(15)->withQueryString();

        return view('admin.categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new category.
     */
    public function create(): View
    {
        return view('admin.categories.create');
    }

    /**
     * Store a newly created category.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:categories'],
            'description' => ['nullable', 'string'],
            'metas' => ['nullable', 'array'],
        ]);

        $validated['slug'] = $validated['slug'] ?: Str::slug($validated['name']);

        $category = Category::create($validated);

        // Handle metas
        if (isset($validated['metas'])) {
            foreach ($validated['metas'] as $meta) {
                if (!empty($meta['key']) && isset($meta['value'])) {
                    $category->setMeta($meta['key'], $meta['value']);
                }
            }
        }

        return redirect()->route('admin.categories.index')
            ->with('success', __('admin.item_created', ['item' => __('admin.categories')]));
    }

    /**
     * Display the specified category.
     */
    public function show(Category $category): View
    {
        $category->load(['posts' => function ($q) {
            $q->latest()->take(10);
        }, 'metas']);

        return view('admin.categories.show', compact('category'));
    }

    /**
     * Show the form for editing the specified category.
     */
    public function edit(Category $category): View
    {
        $category->load('metas');

        return view('admin.categories.edit', compact('category'));
    }

    /**
     * Update the specified category.
     */
    public function update(Request $request, Category $category): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:categories,slug,' . $category->id],
            'description' => ['nullable', 'string'],
            'metas' => ['nullable', 'array'],
        ]);

        $validated['slug'] = $validated['slug'] ?: Str::slug($validated['name']);

        $category->update($validated);

        // Handle metas
        if (isset($validated['metas'])) {
            $category->metas()->delete();
            foreach ($validated['metas'] as $meta) {
                if (!empty($meta['key']) && isset($meta['value'])) {
                    $category->setMeta($meta['key'], $meta['value']);
                }
            }
        }

        return redirect()->route('admin.categories.index')
            ->with('success', __('admin.item_updated', ['item' => __('admin.categories')]));
    }

    /**
     * Remove the specified category.
     */
    public function destroy(Category $category): RedirectResponse
    {
        $category->posts()->detach();
        $category->metas()->delete();
        $category->delete();

        return redirect()->route('admin.categories.index')
            ->with('success', __('admin.item_deleted', ['item' => __('admin.categories')]));
    }
}
