<?php

namespace App\Services\Ai\Providers;

use App\Services\Ai\Contracts\AiProviderInterface;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class OpenAiProvider implements AiProviderInterface
{
    protected string $apiKey;
    protected string $model;
    protected string $baseUrl = 'https://api.openai.com/v1';

    public function __construct(?string $apiKey = null, ?string $model = null)
    {
        $this->apiKey = $apiKey ?? config('ai.providers.openai.api_key');
        $this->model = $model ?? config('ai.providers.openai.model', 'gpt-4');
    }

    /**
     * {@inheritDoc}
     */
    public function generateText(string $prompt, array $options = []): array
    {
        $maxTokens = $options['max_tokens'] ?? config('ai.providers.openai.max_tokens', 4000);
        $temperature = $options['temperature'] ?? 0.7;

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->apiKey,
            'Content-Type' => 'application/json',
        ])->timeout(120)->post("{$this->baseUrl}/chat/completions", [
            'model' => $this->model,
            'messages' => [
                ['role' => 'system', 'content' => 'You are a professional blog writer.'],
                ['role' => 'user', 'content' => $prompt],
            ],
            'max_tokens' => $maxTokens,
            'temperature' => $temperature,
        ]);

        if (!$response->successful()) {
            Log::error('OpenAI API Error', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);
            throw new \RuntimeException('OpenAI API request failed: ' . $response->body());
        }

        $data = $response->json();

        return [
            'content' => $data['choices'][0]['message']['content'] ?? '',
            'tokens_used' => ($data['usage']['prompt_tokens'] ?? 0) + ($data['usage']['completion_tokens'] ?? 0),
            'model' => $data['model'] ?? $this->model,
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function getTokenCount(string $text): int
    {
        // Approximate token count (GPT models use ~4 chars per token on average)
        return (int) ceil(strlen($text) / 4);
    }

    /**
     * {@inheritDoc}
     */
    public function getCostPer1kTokens(): array
    {
        // Pricing for GPT-4 (as of 2024)
        return match ($this->model) {
            'gpt-4' => ['input' => 0.03, 'output' => 0.06],
            'gpt-4-turbo' => ['input' => 0.01, 'output' => 0.03],
            'gpt-4o' => ['input' => 0.005, 'output' => 0.015],
            'gpt-4o-mini' => ['input' => 0.00015, 'output' => 0.0006],
            'gpt-3.5-turbo' => ['input' => 0.0005, 'output' => 0.0015],
            default => ['input' => 0.01, 'output' => 0.03],
        };
    }

    /**
     * {@inheritDoc}
     */
    public function getName(): string
    {
        return 'openai';
    }

    /**
     * {@inheritDoc}
     */
    public function getModel(): string
    {
        return $this->model;
    }

    /**
     * {@inheritDoc}
     */
    public function testConnection(): bool
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
            ])->get("{$this->baseUrl}/models");

            return $response->successful();
        } catch (\Exception $e) {
            Log::error('OpenAI connection test failed', ['error' => $e->getMessage()]);
            return false;
        }
    }
}
