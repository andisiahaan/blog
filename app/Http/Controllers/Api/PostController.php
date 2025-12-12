<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PostController extends Controller
{
    /**
     * Create a new post with tags and categories.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'excerpt' => 'nullable|string|max:500',
            'status' => 'nullable|in:draft,published,scheduled',
            'published_at' => 'nullable|date',
            'tags' => 'nullable|string', // comma-separated
            'categories' => 'nullable|string', // comma-separated
            'featured_image' => 'nullable|string', // base64
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
        ]);

        // Create post
        $post = Post::create([
            'title' => $validated['title'],
            'slug' => Str::slug($validated['title']),
            'content' => $validated['content'],
            'excerpt' => $validated['excerpt'] ?? Str::limit(strip_tags($validated['content']), 200),
            'status' => $validated['status'] ?? 'published',
            'published_at' => $validated['published_at'] ?? ($validated['status'] === 'published' ? now() : null),
            'user_id' => $request->user()->id,
        ]);

        // Handle base64 featured image
        if (!empty($validated['featured_image'])) {
            $imagePath = $this->saveBase64Image($validated['featured_image'], 'posts');
            if ($imagePath) {
                $post->update(['featured_image' => $imagePath]);
            }
        }

        // Handle tags (comma-separated, trim, slug, firstOrCreate)
        if (!empty($validated['tags'])) {
            $tagIds = $this->processTagsOrCategories($validated['tags'], 'tag');
            $post->tags()->sync($tagIds);
        }

        // Handle categories (comma-separated, trim, slug, firstOrCreate)
        if (!empty($validated['categories'])) {
            $categoryIds = $this->processTagsOrCategories($validated['categories'], 'category');
            $post->categories()->sync($categoryIds);
        }

        // Handle metas
        if (!empty($validated['meta_title'])) {
            $post->setMeta('meta_title', $validated['meta_title']);
        }
        if (!empty($validated['meta_description'])) {
            $post->setMeta('meta_description', $validated['meta_description']);
        }

        return response()->json([
            'success' => true,
            'message' => 'Post created successfully',
            'data' => $post->load(['tags', 'categories']),
        ], 201);
    }

    /**
     * Process comma-separated tags or categories.
     */
    private function processTagsOrCategories(string $input, string $type): array
    {
        $items = array_map('trim', explode(',', $input));
        $ids = [];

        foreach ($items as $item) {
            if (empty($item)) continue;

            $slug = Str::slug($item);
            
            if ($type === 'tag') {
                $model = Tag::firstOrCreate(
                    ['slug' => $slug],
                    ['name' => $item]
                );
            } else {
                $model = Category::firstOrCreate(
                    ['slug' => $slug],
                    ['name' => $item]
                );
            }

            $ids[] = $model->id;
        }

        return $ids;
    }

    /**
     * Save base64 image to storage.
     */
    private function saveBase64Image(string $base64, string $folder): ?string
    {
        try {
            // Remove data URI scheme if present
            if (preg_match('/^data:image\/(\w+);base64,/', $base64, $matches)) {
                $extension = $matches[1];
                $base64 = substr($base64, strpos($base64, ',') + 1);
            } else {
                $extension = 'png';
            }

            $imageData = base64_decode($base64);
            if ($imageData === false) {
                return null;
            }

            $filename = $folder . '/' . Str::uuid() . '.' . $extension;
            Storage::disk('public')->put($filename, $imageData);

            return $filename;
        } catch (\Exception $e) {
            return null;
        }
    }
}
