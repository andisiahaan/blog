<?php

namespace App\Console\Commands\Ai;

use App\Jobs\GenerateAiPost;
use App\Models\AiTopic;
use App\Services\Ai\AiContentService;
use Illuminate\Console\Command;

class GenerateContent extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'ai:generate 
                            {--topic= : Specific topic ID to generate from}
                            {--count=1 : Number of posts to generate}
                            {--provider= : AI provider to use (openai, anthropic, google)}
                            {--sync : Run synchronously instead of queuing}';

    /**
     * The console command description.
     */
    protected $description = 'Generate blog posts using AI';

    /**
     * Execute the console command.
     */
    public function handle(AiContentService $service): int
    {
        if (!$service->isEnabled()) {
            $this->error(__('ai.feature_disabled'));
            return self::FAILURE;
        }

        $topicId = $this->option('topic');
        $count = (int) $this->option('count');
        $provider = $this->option('provider');
        $sync = $this->option('sync');

        // Get topics
        if ($topicId) {
            $topic = AiTopic::find($topicId);
            if (!$topic) {
                $this->error(__('ai.topic_not_found'));
                return self::FAILURE;
            }
            $topics = collect([$topic]);
        } else {
            $topics = AiTopic::active()->byPriority()->take($count)->get();
        }

        if ($topics->isEmpty()) {
            $this->warn(__('ai.no_active_topics'));
            return self::SUCCESS;
        }

        $this->info(__('ai.generating_posts', ['count' => $topics->count()]));

        $bar = $this->output->createProgressBar($topics->count());
        $bar->start();

        foreach ($topics as $topic) {
            try {
                if ($sync) {
                    $post = $service->generateArticle($topic, $provider);
                    if ($post) {
                        $this->newLine();
                        $this->info(__('ai.post_generated', ['title' => $post->title]));
                    }
                } else {
                    GenerateAiPost::dispatch($topic, $provider);
                    $this->newLine();
                    $this->info(__('ai.job_dispatched', ['topic' => $topic->name]));
                }
            } catch (\Exception $e) {
                $this->newLine();
                $this->error(__('ai.generation_failed', ['error' => $e->getMessage()]));
            }

            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);
        $this->info(__('ai.generation_complete'));

        return self::SUCCESS;
    }
}
