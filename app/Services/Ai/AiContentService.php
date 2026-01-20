<?php

namespace App\Services\Ai;

use App\Models\AiGenerationLog;
use App\Models\AiTopic;
use App\Models\Category;
use App\Models\Post;
use App\Models\Setting;
use App\Models\Tag;
use App\Services\Ai\Contracts\AiProviderInterface;
use App\Services\Thumbnail\ThumbnailGenerator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class AiContentService
{
    protected AiProviderFactory $providerFactory;
    protected PromptBuilder $promptBuilder;
    protected ThumbnailGenerator $thumbnailGenerator;

    public function __construct(
        AiProviderFactory $providerFactory,
        PromptBuilder $promptBuilder,
        ThumbnailGenerator $thumbnailGenerator
    ) {
        $this->providerFactory = $providerFactory;
        $this->promptBuilder = $promptBuilder;
        $this->thumbnailGenerator = $thumbnailGenerator;
    }

    /**
     * Check if AI feature is enabled.
     */
    public function isEnabled(): bool
    {
        return config('ai.enabled', false);
    }

    /**
     * Generate an article from a topic.
     */
    public function generateArticle(AiTopic $topic, ?string $providerName = null): ?Post
    {
        if (!$this->isEnabled()) {
            throw new \RuntimeException(__('ai.feature_disabled'));
        }

        $provider = $this->providerFactory->make($providerName);
        $startTime = microtime(true);

        // Create log entry
        $log = AiGenerationLog::create([
            'ai_topic_id' => $topic->id,
            'provider' => $provider->getName(),
            'model' => $provider->getModel(),
            'status' => AiGenerationLog::STATUS_PROCESSING,
        ]);

        try {
            // Build prompt
            $prompt = $this->promptBuilder->forTopic($topic)->buildArticlePrompt();
            $log->update(['prompt' => $prompt]);

            // Generate content
            $response = $provider->generateText($prompt, [
                'max_tokens' => 4000,
                'temperature' => 0.7,
            ]);

            // Parse response
            $articleData = $this->parseArticleResponse($response['content']);

            if (!$articleData) {
                throw new \RuntimeException(__('ai.parse_error'));
            }

            // Create post within transaction
            $post = DB::transaction(function () use ($topic, $articleData, $provider, $response, $log, $startTime) {
                // Generate thumbnail
                $featuredImage = $this->thumbnailGenerator->generate($articleData['title']);

                // Create post
                $post = Post::create([
                    'user_id' => $this->getAiUserId(),
                    'title' => $articleData['title'],
                    'slug' => Str::slug($articleData['title']),
                    'excerpt' => $articleData['excerpt'] ?? null,
                    'content' => $articleData['content'],
                    'featured_image' => $featuredImage,
                    'status' => 'published',
                    'published_at' => now(),
                ]);

                // Set meta description
                if (!empty($articleData['meta_description'])) {
                    $post->setMeta('description', $articleData['meta_description']);
                }

                // Assign category from topic
                if ($topic->category_id) {
                    $post->categories()->sync([$topic->category_id]);
                }

                // Handle suggested tags
                if (!empty($articleData['suggested_tags'])) {
                    $this->syncSuggestedTags($post, $articleData['suggested_tags']);
                }

                // Calculate cost
                $cost = $this->calculateCost($provider, $response['tokens_used']);
                $timeMs = (int) ((microtime(true) - $startTime) * 1000);

                // Update log
                $log->markAsSuccess($post, $response['tokens_used'], $cost, $timeMs);

                // Increment topic counter
                $topic->incrementPostsGenerated();

                return $post;
            });

            return $post;
        } catch (\Exception $e) {
            Log::error('AI Article Generation Failed', [
                'topic_id' => $topic->id,
                'error' => $e->getMessage(),
            ]);

            $log->markAsFailed($e->getMessage());

            throw $e;
        }
    }

    /**
     * Parse article response from AI.
     */
    protected function parseArticleResponse(string $content): ?array
    {
        // Try to extract JSON from response
        $content = trim($content);

        // Remove markdown code blocks if present
        if (preg_match('/```(?:json)?\s*([\s\S]*?)```/', $content, $matches)) {
            $content = trim($matches[1]);
        }

        $data = json_decode($content, true);

        if (!$data || empty($data['title']) || empty($data['content'])) {
            // Try to fix common JSON issues
            $content = preg_replace('/,\s*}/', '}', $content);
            $content = preg_replace('/,\s*]/', ']', $content);
            $data = json_decode($content, true);
        }

        if (!$data || empty($data['title']) || empty($data['content'])) {
            return null;
        }

        return [
            'title' => $data['title'],
            'excerpt' => $data['excerpt'] ?? Str::limit(strip_tags($data['content']), 160),
            'content' => $data['content'],
            'meta_description' => $data['meta_description'] ?? null,
            'suggested_tags' => $data['suggested_tags'] ?? [],
        ];
    }

    /**
     * Calculate cost based on tokens used.
     */
    protected function calculateCost(AiProviderInterface $provider, int $tokensUsed): float
    {
        $costs = $provider->getCostPer1kTokens();

        // Assume roughly 50/50 split between input and output
        $avgCost = ($costs['input'] + $costs['output']) / 2;

        return ($tokensUsed / 1000) * $avgCost;
    }

    /**
     * Get AI user ID (admin user who owns AI-generated posts).
     */
    protected function getAiUserId(): int
    {
        // Use first admin user or user ID 1
        return 1;
    }

    /**
     * Sync suggested tags, creating new ones if needed.
     */
    protected function syncSuggestedTags(Post $post, array $tagNames): void
    {
        $tagIds = [];

        foreach ($tagNames as $tagName) {
            $tagName = trim($tagName);
            if (empty($tagName)) {
                continue;
            }

            $tag = Tag::firstOrCreate(
                ['slug' => Str::slug($tagName)],
                ['name' => $tagName]
            );

            $tagIds[] = $tag->id;
        }

        if (!empty($tagIds)) {
            $post->tags()->sync($tagIds);
        }
    }

    /**
     * Get generation statistics.
     */
    public function getStats(): array
    {
        return [
            'total_generated' => AiGenerationLog::successful()->count(),
            'total_failed' => AiGenerationLog::failed()->count(),
            'total_tokens' => AiGenerationLog::successful()->sum('tokens_used'),
            'total_cost' => AiGenerationLog::successful()->sum('cost'),
            'this_month_generated' => AiGenerationLog::successful()
                ->whereMonth('created_at', now()->month)
                ->count(),
            'this_month_cost' => AiGenerationLog::successful()
                ->whereMonth('created_at', now()->month)
                ->sum('cost'),
        ];
    }

    /**
     * Test AI connection.
     */
    public function testConnection(?string $provider = null): bool
    {
        try {
            return $this->providerFactory->make($provider)->testConnection();
        } catch (\Exception $e) {
            Log::error('AI Connection Test Failed', ['error' => $e->getMessage()]);
            return false;
        }
    }
}
