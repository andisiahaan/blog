@extends('admin.layouts.app')
@section('title', __('ai.log_details'))
@section('header', __('ai.log_details'))

@section('content')
<div class="space-y-6 max-w-4xl">
    {{-- Status Header --}}
    <div class="bg-white dark:bg-gray-900 rounded-xl p-5 border border-gray-200 dark:border-gray-800">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
                @switch($log->status)
                    @case('success')
                        <div class="w-12 h-12 rounded-full bg-green-100 dark:bg-green-900/30 flex items-center justify-center">
                            <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        @break
                    @case('failed')
                        <div class="w-12 h-12 rounded-full bg-red-100 dark:bg-red-900/30 flex items-center justify-center">
                            <svg class="w-6 h-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        @break
                    @default
                        <div class="w-12 h-12 rounded-full bg-amber-100 dark:bg-amber-900/30 flex items-center justify-center">
                            <svg class="w-6 h-6 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                @endswitch
                <div>
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">{{ ucfirst($log->status) }}</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ $log->created_at->format('F d, Y H:i:s') }}</p>
                </div>
            </div>
            @if($log->canRetry())
                <form action="{{ route('admin.ai.logs.retry', $log) }}" method="POST">
                    @csrf
                    <button type="submit" class="px-4 py-2 bg-amber-600 hover:bg-amber-700 text-white font-medium rounded-lg transition-colors">
                        {{ __('ai.retry') }}
                    </button>
                </form>
            @endif
        </div>
    </div>

    {{-- Details --}}
    <div class="bg-white dark:bg-gray-900 rounded-xl p-5 border border-gray-200 dark:border-gray-800">
        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">{{ __('ai.log_details') }}</h3>
        <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('ai.topics') }}</dt>
                <dd class="text-gray-900 dark:text-white">{{ $log->topic?->name ?? 'Deleted' }}</dd>
            </div>
            <div>
                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('ai.provider') }}</dt>
                <dd class="text-gray-900 dark:text-white">{{ ucfirst($log->provider) }}</dd>
            </div>
            <div>
                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('ai.model') }}</dt>
                <dd class="text-gray-900 dark:text-white">{{ $log->model }}</dd>
            </div>
            <div>
                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('ai.generation_time') }}</dt>
                <dd class="text-gray-900 dark:text-white">{{ $log->generation_time_ms ? number_format($log->generation_time_ms / 1000, 2) . 's' : '-' }}</dd>
            </div>
            <div>
                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('ai.tokens_used') }}</dt>
                <dd class="text-gray-900 dark:text-white">{{ $log->tokens_used ? number_format($log->tokens_used) : '-' }}</dd>
            </div>
            <div>
                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('ai.cost') }}</dt>
                <dd class="text-gray-900 dark:text-white">{{ $log->cost ? '$' . number_format($log->cost, 6) : '-' }}</dd>
            </div>
            @if($log->generated_title)
                <div class="md:col-span-2">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('ai.generated_title') }}</dt>
                    <dd class="text-gray-900 dark:text-white">{{ $log->generated_title }}</dd>
                </div>
            @endif
            @if($log->post)
                <div class="md:col-span-2">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('ai.view_post') }}</dt>
                    <dd>
                        <a href="{{ route('admin.posts.edit', $log->post) }}" class="text-violet-600 hover:text-violet-700 dark:text-violet-400">
                            {{ $log->post->title }} →
                        </a>
                    </dd>
                </div>
            @endif
        </dl>
    </div>

    @if($log->error_message)
        <div class="bg-red-50 dark:bg-red-900/20 rounded-xl p-5 border border-red-200 dark:border-red-800">
            <h3 class="text-lg font-medium text-red-800 dark:text-red-400 mb-2">{{ __('ai.error_message') }}</h3>
            <pre class="text-sm text-red-700 dark:text-red-300 whitespace-pre-wrap">{{ $log->error_message }}</pre>
        </div>
    @endif

    @if($log->prompt)
        <div class="bg-white dark:bg-gray-900 rounded-xl p-5 border border-gray-200 dark:border-gray-800">
            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">{{ __('ai.prompt') }}</h3>
            <pre class="text-sm text-gray-700 dark:text-gray-300 bg-gray-50 dark:bg-gray-800 p-4 rounded-lg overflow-x-auto whitespace-pre-wrap">{{ $log->prompt }}</pre>
        </div>
    @endif

    {{-- Back --}}
    <div>
        <a href="{{ route('admin.ai.logs.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 dark:bg-gray-800 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300 font-medium rounded-lg transition-colors">
            ← {{ __('admin.back') }}
        </a>
    </div>
</div>
@endsection
