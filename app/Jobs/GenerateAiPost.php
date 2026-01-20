<?php

namespace App\Jobs;

use App\Models\AiTopic;
use App\Services\Ai\AiContentService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class GenerateAiPost implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The number of times the job may be attempted.
     */
    public int $tries = 3;

    /**
     * The number of seconds to wait before retrying the job.
     */
    public int $backoff = 60;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public AiTopic $topic,
        public ?string $provider = null
    ) {
    }

    /**
     * Execute the job.
     */
    public function handle(AiContentService $service): void
    {
        try {
            Log::info('Starting AI post generation', [
                'topic_id' => $this->topic->id,
                'topic_name' => $this->topic->name,
                'provider' => $this->provider,
            ]);

            $post = $service->generateArticle($this->topic, $this->provider);

            if ($post) {
                Log::info('AI post generated successfully', [
                    'topic_id' => $this->topic->id,
                    'post_id' => $post->id,
                    'post_title' => $post->title,
                ]);
            }
        } catch (\Exception $e) {
            Log::error('AI post generation failed', [
                'topic_id' => $this->topic->id,
                'error' => $e->getMessage(),
                'attempt' => $this->attempts(),
            ]);

            // Re-throw to trigger retry
            throw $e;
        }
    }

    /**
     * Handle a job failure.
     */
    public function failed(?\Throwable $exception): void
    {
        Log::error('AI post generation job failed permanently', [
            'topic_id' => $this->topic->id,
            'error' => $exception?->getMessage(),
        ]);
    }
}
