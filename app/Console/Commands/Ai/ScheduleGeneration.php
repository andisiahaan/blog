<?php

namespace App\Console\Commands\Ai;

use App\Jobs\GenerateAiPost;
use App\Models\AiGenerationLog;
use App\Models\AiTopic;
use Illuminate\Console\Command;

class ScheduleGeneration extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'ai:schedule';

    /**
     * The console command description.
     */
    protected $description = 'Schedule AI content generation based on settings';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        // Check if AI and scheduling are enabled (from config/ai.php which reads from .env)
        if (!config('ai.enabled', false)) {
            $this->info(__('ai.feature_disabled'));
            return self::SUCCESS;
        }

        if (!config('ai.scheduling.enabled', false)) {
            $this->info(__('ai.scheduling_disabled'));
            return self::SUCCESS;
        }

        // Check budget limit
        if ($this->isOverBudget()) {
            $this->warn(__('ai.budget_exceeded'));
            return self::SUCCESS;
        }

        $postsPerRun = (int) config('ai.scheduling.posts_per_run', 1);
        
        // Provider is read from config at runtime by AiProviderFactory, pass null
        // to let the factory use the default provider from config

        // Get active topics by priority
        $topics = AiTopic::active()
            ->byPriority()
            ->take($postsPerRun)
            ->get();

        if ($topics->isEmpty()) {
            $this->warn(__('ai.no_active_topics'));
            return self::SUCCESS;
        }

        $this->info(__('ai.scheduling_posts', ['count' => $topics->count()]));

        foreach ($topics as $topic) {
            // Pass null for provider - it will be resolved at runtime from config
            GenerateAiPost::dispatch($topic, null);
            $this->info(__('ai.job_dispatched', ['topic' => $topic->name]));
        }

        return self::SUCCESS;
    }

    /**
     * Check if monthly budget is exceeded.
     */
    protected function isOverBudget(): bool
    {
        $monthlyBudget = (float) config('ai.monthly_budget', 0);

        if ($monthlyBudget <= 0) {
            return false;
        }

        $monthlySpent = AiGenerationLog::successful()
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('cost');

        return $monthlySpent >= $monthlyBudget;
    }
}
