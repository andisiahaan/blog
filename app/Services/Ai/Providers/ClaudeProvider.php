<?php

namespace App\Services\Ai\Providers;

use App\Services\Ai\Contracts\AiProviderInterface;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ClaudeProvider implements AiProviderInterface
{
    protected string $apiKey;
    protected string $model;
    protected string $baseUrl = 'https://api.anthropic.com/v1';

    public function __construct(?string $apiKey = null, ?string $model = null)
    {
        $this->apiKey = $apiKey ?? config('ai.providers.anthropic.api_key');
        $this->model = $model ?? config('ai.providers.anthropic.model', 'claude-3-sonnet-20240229');
    }

    /**
     * {@inheritDoc}
     */
    public function generateText(string $prompt, array $options = []): array
    {
        $maxTokens = $options['max_tokens'] ?? config('ai.providers.anthropic.max_tokens', 4000);

        $response = Http::withHeaders([
            'x-api-key' => $this->apiKey,
            'anthropic-version' => '2023-06-01',
            'Content-Type' => 'application/json',
        ])->timeout(120)->post("{$this->baseUrl}/messages", [
            'model' => $this->model,
            'max_tokens' => $maxTokens,
            'system' => 'You are a professional blog writer.',
            'messages' => [
                ['role' => 'user', 'content' => $prompt],
            ],
        ]);

        if (!$response->successful()) {
            Log::error('Claude API Error', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);
            throw new \RuntimeException('Claude API request failed: ' . $response->body());
        }

        $data = $response->json();

        $content = '';
        foreach ($data['content'] ?? [] as $block) {
            if ($block['type'] === 'text') {
                $content .= $block['text'];
            }
        }

        return [
            'content' => $content,
            'tokens_used' => ($data['usage']['input_tokens'] ?? 0) + ($data['usage']['output_tokens'] ?? 0),
            'model' => $data['model'] ?? $this->model,
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function getTokenCount(string $text): int
    {
        // Approximate token count (Claude uses ~4 chars per token)
        return (int) ceil(strlen($text) / 4);
    }

    /**
     * {@inheritDoc}
     */
    public function getCostPer1kTokens(): array
    {
        // Pricing for Claude models (as of 2024)
        return match ($this->model) {
            'claude-3-opus-20240229' => ['input' => 0.015, 'output' => 0.075],
            'claude-3-sonnet-20240229' => ['input' => 0.003, 'output' => 0.015],
            'claude-3-haiku-20240307' => ['input' => 0.00025, 'output' => 0.00125],
            'claude-3-5-sonnet-20241022' => ['input' => 0.003, 'output' => 0.015],
            default => ['input' => 0.003, 'output' => 0.015],
        };
    }

    /**
     * {@inheritDoc}
     */
    public function getName(): string
    {
        return 'anthropic';
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
            // Claude doesn't have a simple models endpoint, so we'll do a minimal request
            $response = Http::withHeaders([
                'x-api-key' => $this->apiKey,
                'anthropic-version' => '2023-06-01',
                'Content-Type' => 'application/json',
            ])->timeout(10)->post("{$this->baseUrl}/messages", [
                'model' => $this->model,
                'max_tokens' => 10,
                'messages' => [
                    ['role' => 'user', 'content' => 'Hi'],
                ],
            ]);

            return $response->successful();
        } catch (\Exception $e) {
            Log::error('Claude connection test failed', ['error' => $e->getMessage()]);
            return false;
        }
    }
}
