<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Tag;
use Illuminate\Http\Request;

class TagController extends Controller
{
    /**
     * Get all tags.
     */
    public function index()
    {
        $tags = Tag::withCount('posts')
            ->orderBy('name')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $tags,
        ]);
    }

    /**
     * Update a tag (for AI-generated description).
     */
    public function update(Request $request, Tag $tag)
    {
        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'description' => 'sometimes|nullable|string',
        ]);

        $tag->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Tag updated successfully',
            'data' => $tag,
        ]);
    }
}
