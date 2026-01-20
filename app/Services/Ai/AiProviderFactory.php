<?php

namespace App\Services\Ai;

use App\Services\Ai\Contracts\AiProviderInterface;
use App\Services\Ai\Providers\ClaudeProvider;
use App\Services\Ai\Providers\GeminiProvider;
use App\Services\Ai\Providers\OpenAiProvider;
use InvalidArgumentException;

class AiProviderFactory
{
    /**
     * Available providers mapping.
     */
    protected array $providers = [
        'openai' => OpenAiProvider::class,
        'anthropic' => ClaudeProvider::class,
        'google' => GeminiProvider::class,
    ];

    /**
     * Create an AI provider instance.
     */
    public function make(?string $provider = null): AiProviderInterface
    {
        $provider = $provider ?? $this->getDefaultProvider();

        if (!isset($this->providers[$provider])) {
            throw new InvalidArgumentException("Unknown AI provider: {$provider}");
        }

        $apiKey = $this->getApiKey($provider);
        $model = $this->getModel($provider);

        if (empty($apiKey)) {
            throw new InvalidArgumentException("API key not configured for provider: {$provider}. Please set it in .env file.");
        }

        return new $this->providers[$provider]($apiKey, $model);
    }

    /**
     * Get the default provider from config.
     */
    protected function getDefaultProvider(): string
    {
        return config('ai.default_provider', 'openai');
    }

    /**
     * Get the API key for a provider from config.
     */
    protected function getApiKey(string $provider): ?string
    {
        return config("ai.providers.{$provider}.api_key");
    }

    /**
     * Get the model for a provider from config.
     */
    protected function getModel(string $provider): ?string
    {
        return config("ai.providers.{$provider}.model");
    }

    /**
     * Get available providers.
     */
    public function getAvailableProviders(): array
    {
        return [
            'openai' => 'OpenAI (GPT-4)',
            'anthropic' => 'Anthropic (Claude)',
            'google' => 'Google (Gemini)',
        ];
    }

    /**
     * Get available models for a provider.
     */
    public function getModelsForProvider(string $provider): array
    {
        return match ($provider) {
            'openai' => [
                'gpt-4o' => 'GPT-4o (Recommended)',
                'gpt-4o-mini' => 'GPT-4o Mini (Cheaper)',
                'gpt-4-turbo' => 'GPT-4 Turbo',
                'gpt-4' => 'GPT-4',
                'gpt-3.5-turbo' => 'GPT-3.5 Turbo',
            ],
            'anthropic' => [
                'claude-3-5-sonnet-20241022' => 'Claude 3.5 Sonnet (Recommended)',
                'claude-3-opus-20240229' => 'Claude 3 Opus',
                'claude-3-sonnet-20240229' => 'Claude 3 Sonnet',
                'claude-3-haiku-20240307' => 'Claude 3 Haiku (Cheaper)',
            ],
            'google' => [
                'gemini-2.5-flash' => 'Gemini 2.5 Flash (Recommended)',
                'gemini-2.5-pro' => 'Gemini 2.5 Pro',
                'gemini-1.5-pro' => 'Gemini 1.5 Pro',
                'gemini-1.5-flash' => 'Gemini 1.5 Flash',
            ],
            default => [],
        };
    }

    /**
     * Check if a provider is configured.
     */
    public function isProviderConfigured(string $provider): bool
    {
        $apiKey = $this->getApiKey($provider);
        return !empty($apiKey);
    }

    /**
     * Get current configuration info (for display in admin).
     */
    public function getConfigurationInfo(): array
    {
        $provider = $this->getDefaultProvider();
        $model = $this->getModel($provider);
        $isConfigured = $this->isProviderConfigured($provider);

        return [
            'enabled' => config('ai.enabled', false),
            'provider' => $provider,
            'provider_name' => $this->getAvailableProviders()[$provider] ?? $provider,
            'model' => $model,
            'is_configured' => $isConfigured,
            'scheduling_enabled' => config('ai.scheduling.enabled', false),
            'schedule_frequency' => config('ai.scheduling.frequency', 'daily'),
            'posts_per_run' => config('ai.scheduling.posts_per_run', 1),
            'monthly_budget' => config('ai.monthly_budget', 0),
        ];
    }
}
