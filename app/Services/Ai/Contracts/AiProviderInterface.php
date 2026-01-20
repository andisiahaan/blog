<?php

namespace App\Services\Ai\Contracts;

interface AiProviderInterface
{
    /**
     * Generate text content based on a prompt.
     *
     * @param string $prompt The prompt to send to the AI
     * @param array $options Additional options (max_tokens, temperature, etc.)
     * @return array{content: string, tokens_used: int, model: string}
     */
    public function generateText(string $prompt, array $options = []): array;

    /**
     * Get the token count for a given text.
     *
     * @param string $text The text to count tokens for
     * @return int
     */
    public function getTokenCount(string $text): int;

    /**
     * Get the cost per 1000 tokens for the current model.
     *
     * @return array{input: float, output: float}
     */
    public function getCostPer1kTokens(): array;

    /**
     * Get the provider name.
     *
     * @return string
     */
    public function getName(): string;

    /**
     * Get the current model name.
     *
     * @return string
     */
    public function getModel(): string;

    /**
     * Test the connection to the API.
     *
     * @return bool
     */
    public function testConnection(): bool;
}
