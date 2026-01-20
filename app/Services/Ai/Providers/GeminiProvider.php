<?php

namespace App\Services\Ai\Providers;

use App\Services\Ai\Contracts\AiProviderInterface;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GeminiProvider implements AiProviderInterface
{
    protected string $apiKey;
    protected string $model;
    protected string $baseUrl = 'https://generativelanguage.googleapis.com/v1beta';

    public function __construct(?string $apiKey = null, ?string $model = null)
    {
        $this->apiKey = $apiKey ?? config('ai.providers.google.api_key');
        $this->model = $model ?? config('ai.providers.google.model', 'gemini-pro');
    }

    /**
     * {@inheritDoc}
     */
    public function generateText(string $prompt, array $options = []): array
    {
        $maxTokens = $options['max_tokens'] ?? config('ai.providers.google.max_tokens', 4000);

        $response = Http::timeout(120)->post(
            "{$this->baseUrl}/models/{$this->model}:generateContent?key={$this->apiKey}",
            [
                'contents' => [
                    [
                        'role' => 'user',
                        'parts' => [
                            ['text' => "You are a professional blog writer.\n\n" . $prompt],
                        ],
                    ],
                ],
                'generationConfig' => [
                    'maxOutputTokens' => $maxTokens,
                    'temperature' => $options['temperature'] ?? 0.7,
                ],
            ]
        );

        if (!$response->successful()) {
            Log::error('Gemini API Error', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);
            throw new \RuntimeException('Gemini API request failed: ' . $response->body());
        }

        $data = $response->json();

        $content = '';
        if (isset($data['candidates'][0]['content']['parts'])) {
            foreach ($data['candidates'][0]['content']['parts'] as $part) {
                $content .= $part['text'] ?? '';
            }
        }

        // Gemini doesn't always return token counts in the same way
        $tokensUsed = 0;
        if (isset($data['usageMetadata'])) {
            $tokensUsed = ($data['usageMetadata']['promptTokenCount'] ?? 0) +
                          ($data['usageMetadata']['candidatesTokenCount'] ?? 0);
        }

        return [
            'content' => $content,
            'tokens_used' => $tokensUsed,
            'model' => $this->model,
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function getTokenCount(string $text): int
    {
        // Approximate token count
        return (int) ceil(strlen($text) / 4);
    }

    /**
     * {@inheritDoc}
     */
    public function getCostPer1kTokens(): array
    {
        // Pricing for Gemini models (as of 2024)
        return match ($this->model) {
            'gemini-pro' => ['input' => 0.00025, 'output' => 0.0005],
            'gemini-1.5-pro' => ['input' => 0.00125, 'output' => 0.005],
            'gemini-1.5-flash' => ['input' => 0.000075, 'output' => 0.0003],
            default => ['input' => 0.00025, 'output' => 0.0005],
        };
    }

    /**
     * {@inheritDoc}
     */
    public function getName(): string
    {
        return 'google';
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
            $response = Http::timeout(10)->get(
                "{$this->baseUrl}/models/{$this->model}?key={$this->apiKey}"
            );

            return $response->successful();
        } catch (\Exception $e) {
            Log::error('Gemini connection test failed', ['error' => $e->getMessage()]);
            return false;
        }
    }
}
