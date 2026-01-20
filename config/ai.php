<?php

return [
    /*
    |--------------------------------------------------------------------------
    | AI Feature Toggle
    |--------------------------------------------------------------------------
    |
    | Enable or disable the AI content generation feature.
    |
    */
    'enabled' => env('AI_ENABLED', false),

    /*
    |--------------------------------------------------------------------------
    | Default Provider
    |--------------------------------------------------------------------------
    |
    | The default AI provider to use for content generation.
    | Supported: "openai", "anthropic", "google"
    |
    */
    'default_provider' => env('AI_PROVIDER', 'openai'),

    /*
    |--------------------------------------------------------------------------
    | Provider Configurations
    |--------------------------------------------------------------------------
    |
    | Configuration for each supported AI provider.
    | All API keys and models are read from .env file.
    |
    */
    'providers' => [
        'openai' => [
            'api_key' => env('OPENAI_API_KEY'),
            'model' => env('OPENAI_MODEL', 'gpt-4o'),
            'max_tokens' => 4000,
        ],
        'anthropic' => [
            'api_key' => env('ANTHROPIC_API_KEY'),
            'model' => env('ANTHROPIC_MODEL', 'claude-3-5-sonnet-20241022'),
            'max_tokens' => 4000,
        ],
        'google' => [
            'api_key' => env('GOOGLE_AI_API_KEY'),
            'model' => env('GOOGLE_AI_MODEL', 'gemini-2.5-flash'),
            'max_tokens' => 4000,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Content Defaults
    |--------------------------------------------------------------------------
    |
    | Default settings for generated content.
    |
    */
    'defaults' => [
        'language' => 'id',
        'tone' => 'professional',
        'min_words' => 800,
        'max_words' => 1500,
    ],

    /*
    |--------------------------------------------------------------------------
    | Scheduling
    |--------------------------------------------------------------------------
    |
    | Automatic content generation scheduling settings.
    |
    */
    'scheduling' => [
        'enabled' => env('AI_AUTO_SCHEDULE', false),
        'frequency' => env('AI_SCHEDULE_FREQUENCY', 'daily'),
        'posts_per_run' => env('AI_POSTS_PER_RUN', 1),
    ],

    /*
    |--------------------------------------------------------------------------
    | Budget
    |--------------------------------------------------------------------------
    |
    | Monthly budget limit for AI API costs (in USD).
    | Set to 0 for no limit.
    |
    */
    'monthly_budget' => env('AI_MONTHLY_BUDGET', 0),
];
