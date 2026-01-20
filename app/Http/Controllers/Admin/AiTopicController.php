<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Jobs\GenerateAiPost;
use App\Models\AiTopic;
use App\Models\Category;
use App\Services\Ai\AiContentService;
use App\Services\Ai\PromptBuilder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AiTopicController extends Controller
{
    /**
     * Display a listing of AI topics.
     */
    public function index(Request $request): View
    {
        $query = AiTopic::with('category');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('keywords', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        $topics = $query->orderByDesc('priority')
            ->orderByDesc('created_at')
            ->paginate(15)
            ->withQueryString();

        return view('admin.ai.topics.index', compact('topics'));
    }

    /**
     * Show the form for creating a new topic.
     */
    public function create(): View
    {
        $categories = Category::orderBy('name')->get();
        $tones = PromptBuilder::getAvailableTones();
        $languages = PromptBuilder::getAvailableLanguages();

        return view('admin.ai.topics.create', compact('categories', 'tones', 'languages'));
    }

    /**
     * Store a newly created topic.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'keywords' => ['nullable', 'string'],
            'tone' => ['required', 'string'],
            'language' => ['required', 'string', 'size:2'],
            'min_words' => ['required', 'integer', 'min:100', 'max:5000'],
            'max_words' => ['required', 'integer', 'min:100', 'max:10000', 'gte:min_words'],
            'category_id' => ['nullable', 'exists:categories,id'],
            'is_active' => ['nullable', 'boolean'],
            'priority' => ['nullable', 'integer', 'min:0', 'max:100'],
        ]);

        $validated['is_active'] = $validated['is_active'] ?? false;
        $validated['priority'] = $validated['priority'] ?? 0;

        AiTopic::create($validated);

        return redirect()->route('admin.ai.topics.index')
            ->with('success', __('ai.topic_created'));
    }

    /**
     * Display the specified topic.
     */
    public function show(AiTopic $topic): View
    {
        $topic->load(['category', 'logs' => function ($query) {
            $query->latest()->take(10);
        }]);

        return view('admin.ai.topics.show', compact('topic'));
    }

    /**
     * Show the form for editing the specified topic.
     */
    public function edit(AiTopic $topic): View
    {
        $categories = Category::orderBy('name')->get();
        $tones = PromptBuilder::getAvailableTones();
        $languages = PromptBuilder::getAvailableLanguages();

        return view('admin.ai.topics.edit', compact('topic', 'categories', 'tones', 'languages'));
    }

    /**
     * Update the specified topic.
     */
    public function update(Request $request, AiTopic $topic): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'keywords' => ['nullable', 'string'],
            'tone' => ['required', 'string'],
            'language' => ['required', 'string', 'size:2'],
            'min_words' => ['required', 'integer', 'min:100', 'max:5000'],
            'max_words' => ['required', 'integer', 'min:100', 'max:10000', 'gte:min_words'],
            'category_id' => ['nullable', 'exists:categories,id'],
            'is_active' => ['nullable', 'boolean'],
            'priority' => ['nullable', 'integer', 'min:0', 'max:100'],
        ]);

        $validated['is_active'] = $validated['is_active'] ?? false;

        $topic->update($validated);

        return redirect()->route('admin.ai.topics.index')
            ->with('success', __('ai.topic_updated'));
    }

    /**
     * Remove the specified topic.
     */
    public function destroy(AiTopic $topic): RedirectResponse
    {
        $topic->delete();

        return redirect()->route('admin.ai.topics.index')
            ->with('success', __('ai.topic_deleted'));
    }

    /**
     * Generate a post from this topic.
     */
    public function generate(Request $request, AiTopic $topic, AiContentService $service): RedirectResponse
    {
        if (!$service->isEnabled()) {
            return redirect()->route('admin.ai.topics.index')
                ->with('error', __('ai.feature_disabled'));
        }

        $sync = $request->boolean('sync', false);

        try {
            if ($sync) {
                $post = $service->generateArticle($topic);
                return redirect()->route('admin.ai.topics.index')
                    ->with('success', __('ai.post_generated', ['title' => $post->title]));
            }

            GenerateAiPost::dispatch($topic);
            return redirect()->route('admin.ai.topics.index')
                ->with('success', __('ai.job_dispatched', ['topic' => $topic->name]));
        } catch (\Exception $e) {
            return redirect()->route('admin.ai.topics.index')
                ->with('error', __('ai.generation_failed', ['error' => $e->getMessage()]));
        }
    }
}
