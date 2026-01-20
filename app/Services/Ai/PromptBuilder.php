<?php

namespace App\Services\Ai;

use App\Models\AiTopic;

class PromptBuilder
{
    protected AiTopic $topic;
    protected string $language = 'id';
    protected string $tone = 'professional';
    protected int $minWords = 800;
    protected int $maxWords = 1500;

    /**
     * Create a new prompt builder for a topic.
     */
    public function forTopic(AiTopic $topic): self
    {
        $this->topic = $topic;
        $this->language = $topic->language ?? 'id';
        $this->tone = $topic->tone ?? 'professional';
        $this->minWords = $topic->min_words ?? 800;
        $this->maxWords = $topic->max_words ?? 1500;

        return $this;
    }

    /**
     * Set language manually.
     */
    public function language(string $language): self
    {
        $this->language = $language;

        return $this;
    }

    /**
     * Set tone manually.
     */
    public function tone(string $tone): self
    {
        $this->tone = $tone;

        return $this;
    }

    /**
     * Set word count range.
     */
    public function wordCount(int $min, int $max): self
    {
        $this->minWords = $min;
        $this->maxWords = $max;

        return $this;
    }

    /**
     * Build the article generation prompt.
     */
    public function buildArticlePrompt(?string $customTopic = null): string
    {
        $topicName = $customTopic ?? ($this->topic->name ?? 'general topic');
        $description = $this->topic->description ?? '';
        $keywords = $this->topic->keywords ?? '';

        $languageName = $this->getLanguageName();
        $toneDescription = $this->getToneDescription();

        $prompt = <<<PROMPT
Write a comprehensive, engaging blog article about the following topic:

**Topic**: {$topicName}
PROMPT;

        if ($description) {
            $prompt .= "\n**Description**: {$description}";
        }

        if ($keywords) {
            $prompt .= "\n**Keywords to include**: {$keywords}";
        }

        $prompt .= <<<PROMPT


**Requirements**:
1. Write in {$languageName}
2. Use a {$toneDescription} tone
3. Article length should be between {$this->minWords} and {$this->maxWords} words
4. Include an engaging introduction that hooks the reader
5. Use proper HTML formatting:
   - Use <h2> for main sections, <h3> for subsections
   - Use <p> for paragraphs
   - Use <strong> for important terms and keywords (good for SEO)
   - Use <em> for emphasis
   - Use <ul> and <li> for lists
   - Use <blockquote> for quotes if needed
6. Include practical examples, tips, or actionable advice where appropriate
7. End with a compelling conclusion
8. Make the content SEO-friendly and informative
9. Do NOT use Markdown syntax (no ##, **, *, etc.) - use only HTML tags

**Output Format**:
Return the response in the following JSON format:
```json
{
    "title": "The article title (compelling and SEO-optimized, plain text without HTML)",
    "excerpt": "A 1-2 sentence summary of the article (max 160 characters, plain text)",
    "content": "The full article content with HTML formatting (h2, h3, p, strong, ul, li, etc.)",
    "meta_description": "SEO meta description (max 160 characters, plain text)",
    "suggested_tags": ["tag1", "tag2", "tag3"]
}
```

Important: Return ONLY the JSON object, no additional text before or after.
PROMPT;

        return $prompt;
    }

    /**
     * Build a prompt for generating just a title.
     */
    public function buildTitlePrompt(): string
    {
        $topicName = $this->topic->name ?? 'general topic';
        $keywords = $this->topic->keywords ?? '';
        $languageName = $this->getLanguageName();

        return <<<PROMPT
Generate 5 unique, compelling blog post titles about "{$topicName}".

Keywords: {$keywords}
Language: {$languageName}

Requirements:
- Each title should be SEO-optimized
- Use power words to increase engagement
- Keep titles between 50-70 characters
- Mix different title formats (how-to, listicles, questions, etc.)

Return as a JSON array of strings:
["Title 1", "Title 2", "Title 3", "Title 4", "Title 5"]
PROMPT;
    }

    /**
     * Get language display name.
     */
    protected function getLanguageName(): string
    {
        return match ($this->language) {
            'id' => 'Bahasa Indonesia',
            'en' => 'English',
            'es' => 'Spanish',
            'fr' => 'French',
            'de' => 'German',
            'pt' => 'Portuguese',
            'zh' => 'Chinese',
            'ja' => 'Japanese',
            'ko' => 'Korean',
            default => 'English',
        };
    }

    /**
     * Get tone description.
     */
    protected function getToneDescription(): string
    {
        return match ($this->tone) {
            'professional' => 'professional and authoritative',
            'casual' => 'casual and conversational',
            'friendly' => 'friendly and approachable',
            'formal' => 'formal and academic',
            'humorous' => 'light-hearted and humorous',
            'inspirational' => 'inspirational and motivating',
            'educational' => 'educational and informative',
            default => 'professional',
        };
    }

    /**
     * Get available tones.
     */
    public static function getAvailableTones(): array
    {
        return [
            'professional' => __('ai.tone_professional'),
            'casual' => __('ai.tone_casual'),
            'friendly' => __('ai.tone_friendly'),
            'formal' => __('ai.tone_formal'),
            'humorous' => __('ai.tone_humorous'),
            'inspirational' => __('ai.tone_inspirational'),
            'educational' => __('ai.tone_educational'),
        ];
    }

    /**
     * Get available languages.
     */
    public static function getAvailableLanguages(): array
    {
        return [
            'id' => 'Bahasa Indonesia',
            'en' => 'English',
            'es' => 'Español',
            'fr' => 'Français',
            'de' => 'Deutsch',
            'pt' => 'Português',
            'zh' => '中文',
            'ja' => '日本語',
            'ko' => '한국어',
        ];
    }
}
