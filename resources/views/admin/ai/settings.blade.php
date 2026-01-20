@extends('admin.layouts.app')
@section('title', __('ai.settings_title'))
@section('header', __('ai.settings_title'))

@section('content')
<div class="space-y-6">
    {{-- Configuration Status --}}
    <div class="bg-white dark:bg-gray-900 rounded-xl p-5 border border-gray-200 dark:border-gray-800">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-medium text-gray-900 dark:text-white">{{ __('ai.api_configuration') }}</h3>
            @if($config['enabled'])
                <span class="inline-flex items-center px-3 py-1 text-sm font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400 rounded-full">
                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20"><circle cx="10" cy="10" r="3"/></svg>
                    {{ __('admin.active') }}
                </span>
            @else
                <span class="inline-flex items-center px-3 py-1 text-sm font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-400 rounded-full">
                    {{ __('admin.inactive') }}
                </span>
            @endif
        </div>

        <div class="bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800 rounded-lg p-4 mb-4">
            <div class="flex">
                <svg class="w-5 h-5 text-amber-500 mr-2 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <div>
                    <p class="text-sm font-medium text-amber-800 dark:text-amber-300">{{ __('ai.config_from_env') }}</p>
                    <p class="text-sm text-amber-700 dark:text-amber-400 mt-1">{{ __('ai.config_from_env_help') }}</p>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            {{-- Provider --}}
            <div class="p-4 bg-gray-50 dark:bg-gray-800 rounded-lg">
                <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('ai.provider') }}</p>
                <p class="text-lg font-medium text-gray-900 dark:text-white">{{ $config['provider_name'] }}</p>
                <p class="text-xs text-gray-400">AI_PROVIDER={{ $config['provider'] }}</p>
            </div>

            {{-- Model --}}
            <div class="p-4 bg-gray-50 dark:bg-gray-800 rounded-lg">
                <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('ai.model') }}</p>
                <p class="text-lg font-medium text-gray-900 dark:text-white">{{ $config['model'] }}</p>
            </div>

            {{-- API Key Status --}}
            <div class="p-4 bg-gray-50 dark:bg-gray-800 rounded-lg">
                <p class="text-sm text-gray-500 dark:text-gray-400">API Key</p>
                @if($config['is_configured'])
                    <p class="text-lg font-medium text-green-600 dark:text-green-400">✓ {{ __('ai.configured') }}</p>
                @else
                    <p class="text-lg font-medium text-red-600 dark:text-red-400">✗ {{ __('ai.not_configured') }}</p>
                @endif
            </div>
        </div>

        {{-- Test Connection --}}
        <div class="mt-4 flex gap-2">
            <form action="{{ route('admin.ai.settings.test') }}" method="POST">
                @csrf
                <button type="submit" class="px-4 py-2 bg-violet-600 hover:bg-violet-700 text-white font-medium rounded-lg transition-colors">
                    {{ __('ai.test_connection') }}
                </button>
            </form>
        </div>
    </div>

    {{-- Scheduling Info --}}
    <div class="bg-white dark:bg-gray-900 rounded-xl p-5 border border-gray-200 dark:border-gray-800">
        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">{{ __('ai.scheduling') }}</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="p-4 bg-gray-50 dark:bg-gray-800 rounded-lg">
                <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('ai.auto_schedule') }}</p>
                <p class="text-lg font-medium text-gray-900 dark:text-white">
                    {{ $config['scheduling_enabled'] ? __('admin.active') : __('admin.inactive') }}
                </p>
                <p class="text-xs text-gray-400">AI_AUTO_SCHEDULE</p>
            </div>
            <div class="p-4 bg-gray-50 dark:bg-gray-800 rounded-lg">
                <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('ai.schedule_frequency') }}</p>
                <p class="text-lg font-medium text-gray-900 dark:text-white">{{ ucfirst(str_replace('_', ' ', $config['schedule_frequency'])) }}</p>
                <p class="text-xs text-gray-400">AI_SCHEDULE_FREQUENCY</p>
            </div>
            <div class="p-4 bg-gray-50 dark:bg-gray-800 rounded-lg">
                <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('ai.posts_per_run') }}</p>
                <p class="text-lg font-medium text-gray-900 dark:text-white">{{ $config['posts_per_run'] }}</p>
                <p class="text-xs text-gray-400">AI_POSTS_PER_RUN</p>
            </div>
        </div>
    </div>

    {{-- Stats Cards --}}
    <div class="bg-white dark:bg-gray-900 rounded-xl p-5 border border-gray-200 dark:border-gray-800">
        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">{{ __('ai.stats_title') }}</h3>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="p-4 bg-gray-50 dark:bg-gray-800 rounded-lg">
                <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('ai.total_generated') }}</p>
                <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ number_format($stats['total_generated'] ?? 0) }}</p>
            </div>
            <div class="p-4 bg-gray-50 dark:bg-gray-800 rounded-lg">
                <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('ai.this_month') }}</p>
                <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ number_format($stats['this_month_generated'] ?? 0) }}</p>
            </div>
            <div class="p-4 bg-gray-50 dark:bg-gray-800 rounded-lg">
                <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('ai.total_tokens') }}</p>
                <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ number_format($stats['total_tokens'] ?? 0) }}</p>
            </div>
            <div class="p-4 bg-gray-50 dark:bg-gray-800 rounded-lg">
                <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('ai.total_cost') }}</p>
                <p class="text-2xl font-bold text-gray-900 dark:text-white">${{ number_format($stats['total_cost'] ?? 0, 2) }}</p>
            </div>
        </div>
    </div>

    {{-- Thumbnail Images --}}
    <div class="bg-white dark:bg-gray-900 rounded-xl p-5 border border-gray-200 dark:border-gray-800">
        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">{{ __('ai.thumbnail_settings') }}</h3>
        <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">{{ __('ai.thumbnail_images_help') }}</p>

        {{-- Upload Form --}}
        <form action="{{ route('admin.ai.settings.thumbnails.upload') }}" method="POST" enctype="multipart/form-data" class="mb-4">
            @csrf
            <div class="flex items-center gap-4">
                <input type="file" name="images[]" multiple accept="image/*"
                       class="flex-1 text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-violet-50 file:text-violet-700 hover:file:bg-violet-100 dark:file:bg-violet-900/30 dark:file:text-violet-400">
                <button type="submit" class="px-4 py-2 bg-violet-600 hover:bg-violet-700 text-white font-medium rounded-lg transition-colors">
                    {{ __('ai.upload_images') }}
                </button>
            </div>
        </form>

        {{-- Existing Images --}}
        @if(count($thumbnailImages) > 0)
            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
                @foreach($thumbnailImages as $image)
                    <div class="relative group">
                        <img src="{{ Storage::disk(config('filesystems.default'))->url($image) }}" alt="Thumbnail" class="w-full h-24 object-cover rounded-lg">
                        <form action="{{ route('admin.ai.settings.thumbnails.delete') }}" method="POST" class="absolute top-1 right-1 opacity-0 group-hover:opacity-100 transition-opacity">
                            @csrf
                            @method('DELETE')
                            <input type="hidden" name="path" value="{{ $image }}">
                            <button type="submit" class="p-1 bg-red-600 hover:bg-red-700 text-white rounded-full">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </button>
                        </form>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-sm text-gray-500 dark:text-gray-400 italic">{{ __('ai.no_thumbnails') }}</p>
        @endif
    </div>

    {{-- .env Configuration Guide --}}
    <div class="bg-white dark:bg-gray-900 rounded-xl p-5 border border-gray-200 dark:border-gray-800">
        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">{{ __('ai.env_guide') }}</h3>
        <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">{{ __('ai.env_guide_help') }}</p>
        <pre class="bg-gray-50 dark:bg-gray-800 p-4 rounded-lg text-sm overflow-x-auto"><code># AI Auto Blog Configuration
AI_ENABLED=true
AI_PROVIDER=openai

# OpenAI
OPENAI_API_KEY=sk-your-api-key-here
OPENAI_MODEL=gpt-4o

# Anthropic (Claude)
ANTHROPIC_API_KEY=sk-ant-your-api-key-here
ANTHROPIC_MODEL=claude-3-5-sonnet-20241022

# Google (Gemini)
GOOGLE_AI_API_KEY=your-api-key-here
GOOGLE_AI_MODEL=gemini-2.5-flash

# Scheduling
AI_AUTO_SCHEDULE=false
AI_SCHEDULE_FREQUENCY=daily
AI_POSTS_PER_RUN=1
AI_MONTHLY_BUDGET=10</code></pre>
    </div>
</div>
@endsection
