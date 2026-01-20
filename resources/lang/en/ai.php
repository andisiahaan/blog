<?php

return [
    /*
    |--------------------------------------------------------------------------
    | AI Auto Blog Language Lines
    |--------------------------------------------------------------------------
    */

    // Menu & Navigation
    'ai_blog' => 'AI Auto Blog',
    'settings' => 'AI Settings',
    'topics' => 'Topics',
    'logs' => 'Generation Logs',
    'stats' => 'Statistics',

    // Settings Page
    'settings_title' => 'AI Configuration',
    'settings_description' => 'Configure AI providers and content generation settings.',
    'settings_updated' => 'AI settings updated successfully.',

    // API Configuration
    'api_configuration' => 'API Configuration',
    'enable_ai' => 'Enable AI',
    'enable_ai_help' => 'Turn on AI content generation feature.',
    'provider' => 'AI Provider',
    'select_provider' => 'Select Provider',
    'api_key' => 'API Key',
    'api_key_placeholder' => 'Enter your API key',
    'api_key_help' => 'Your API key will be stored securely.',
    'model' => 'Model',
    'select_model' => 'Select Model',
    'test_connection' => 'Test Connection',
    'connection_success' => 'Connection successful! AI provider is working.',
    'connection_failed' => 'Connection failed. Please check your API key.',

    // Scheduling
    'scheduling' => 'Scheduling',
    'auto_schedule' => 'Auto Generate',
    'auto_schedule_help' => 'Automatically generate posts based on schedule.',
    'schedule_frequency' => 'Frequency',
    'frequency_hourly' => 'Every Hour',
    'frequency_every_6_hours' => 'Every 6 Hours',
    'frequency_every_12_hours' => 'Every 12 Hours',
    'frequency_daily' => 'Daily',
    'frequency_weekly' => 'Weekly',
    'posts_per_run' => 'Posts Per Run',
    'posts_per_run_help' => 'Number of posts to generate per scheduled run.',

    // Budget
    'budget' => 'Budget Control',
    'monthly_budget' => 'Monthly Budget (USD)',
    'monthly_budget_help' => 'Set to 0 for no limit.',
    'current_usage' => 'Current Month Usage',
    'budget_exceeded' => 'Monthly budget limit has been reached.',

    // Thumbnail Settings
    'thumbnail_settings' => 'Thumbnail Images',
    'thumbnail_images' => 'Background Images',
    'thumbnail_images_help' => 'Upload images for featured image generation. These will be randomly selected.',
    'upload_images' => 'Upload Images',
    'thumbnails_uploaded' => 'Thumbnail images uploaded successfully.',
    'thumbnail_deleted' => 'Thumbnail image deleted.',
    'no_thumbnails' => 'No background images uploaded yet.',

    // Topics
    'topics_title' => 'AI Topics',
    'topics_description' => 'Manage topics for AI content generation.',
    'create_topic' => 'Create Topic',
    'edit_topic' => 'Edit Topic',
    'topic_name' => 'Topic Name',
    'topic_description' => 'Description',
    'topic_keywords' => 'Keywords',
    'topic_keywords_help' => 'Comma-separated keywords to include in generated content.',
    'topic_tone' => 'Writing Tone',
    'topic_language' => 'Language',
    'topic_word_count' => 'Word Count',
    'min_words' => 'Minimum Words',
    'max_words' => 'Maximum Words',
    'assign_category' => 'Assign to Category',
    'assign_category_help' => 'Generated posts will be automatically assigned to this category.',
    'topic_priority' => 'Priority',
    'topic_priority_help' => 'Higher priority topics are generated first.',
    'topic_active' => 'Active',
    'posts_generated' => 'Posts Generated',
    'topic_created' => 'Topic created successfully.',
    'topic_updated' => 'Topic updated successfully.',
    'topic_deleted' => 'Topic deleted successfully.',
    'topic_not_found' => 'Topic not found.',
    'no_active_topics' => 'No active topics found.',

    // Tones
    'tone_professional' => 'Professional',
    'tone_casual' => 'Casual',
    'tone_friendly' => 'Friendly',
    'tone_formal' => 'Formal',
    'tone_humorous' => 'Humorous',
    'tone_inspirational' => 'Inspirational',
    'tone_educational' => 'Educational',

    // Generation
    'generate' => 'Generate',
    'generate_now' => 'Generate Now',
    'generating_posts' => 'Generating :count post(s)...',
    'post_generated' => 'Post generated: :title',
    'job_dispatched' => 'Generation job dispatched for topic: :topic',
    'generation_complete' => 'Generation complete.',
    'generation_failed' => 'Generation failed: :error',
    'feature_disabled' => 'AI feature is disabled.',
    'parse_error' => 'Failed to parse AI response.',

    // Logs
    'logs_title' => 'Generation Logs',
    'logs_description' => 'View history of AI content generation.',
    'log_details' => 'Log Details',
    'status' => 'Status',
    'status_pending' => 'Pending',
    'status_processing' => 'Processing',
    'status_success' => 'Success',
    'status_failed' => 'Failed',
    'tokens_used' => 'Tokens Used',
    'cost' => 'Cost',
    'generation_time' => 'Generation Time',
    'prompt' => 'Prompt',
    'generated_title' => 'Generated Title',
    'error_message' => 'Error Message',
    'retry' => 'Retry',
    'retry_dispatched' => 'Retry job dispatched.',
    'retry_failed' => 'Retry failed: :error',
    'cannot_retry' => 'This log cannot be retried.',
    'logs_cleaned' => ':count old logs deleted.',
    'cleanup_logs' => 'Cleanup Old Logs',

    // Stats
    'stats_title' => 'AI Statistics',
    'total_generated' => 'Total Generated',
    'total_failed' => 'Total Failed',
    'total_tokens' => 'Total Tokens',
    'total_cost' => 'Total Cost',
    'this_month' => 'This Month',
    'daily_chart' => 'Daily Generation',
    'provider_breakdown' => 'By Provider',

    // Thumbnail Generator
    'position_center' => 'Center',
    'position_top' => 'Top',
    'position_bottom' => 'Bottom',
    'font_normal' => 'Normal',
    'font_bold' => 'Bold',
    'font_italic' => 'Italic',

    // Filters
    'filter_by_status' => 'Filter by Status',
    'filter_by_provider' => 'Filter by Provider',
    'date_from' => 'From Date',
    'date_to' => 'To Date',
    'clear_filters' => 'Clear Filters',

    // Actions
    'view_post' => 'View Post',
    'view_logs' => 'View Logs',
    'scheduling_disabled' => 'Auto scheduling is disabled.',

    // .env Configuration
    'config_from_env' => 'Configuration is read from .env file',
    'config_from_env_help' => 'API keys, models, and scheduling settings are configured via .env file for security.',
    'configured' => 'Configured',
    'not_configured' => 'Not Configured',
    'env_guide' => '.env Configuration Guide',
    'env_guide_help' => 'Add these variables to your .env file to configure AI features:',
];
