@extends('admin.layouts.app')
@section('title', __('ai.logs_title'))
@section('header', __('ai.logs_title'))

@section('actions')
<a href="{{ route('admin.ai.logs.stats') }}" class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 dark:bg-gray-800 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300 text-sm font-medium rounded-lg transition-colors">
    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
    {{ __('ai.stats') }}
</a>
@endsection

@section('content')
<div class="space-y-4">
    {{-- Stats Summary --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="bg-white dark:bg-gray-900 rounded-xl p-4 border border-gray-200 dark:border-gray-800">
            <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('admin.all') }}</p>
            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ number_format($stats['total']) }}</p>
        </div>
        <div class="bg-white dark:bg-gray-900 rounded-xl p-4 border border-gray-200 dark:border-gray-800">
            <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('ai.status_success') }}</p>
            <p class="text-2xl font-bold text-green-600">{{ number_format($stats['successful']) }}</p>
        </div>
        <div class="bg-white dark:bg-gray-900 rounded-xl p-4 border border-gray-200 dark:border-gray-800">
            <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('ai.status_failed') }}</p>
            <p class="text-2xl font-bold text-red-600">{{ number_format($stats['failed']) }}</p>
        </div>
        <div class="bg-white dark:bg-gray-900 rounded-xl p-4 border border-gray-200 dark:border-gray-800">
            <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('ai.status_pending') }}</p>
            <p class="text-2xl font-bold text-amber-600">{{ number_format($stats['pending']) }}</p>
        </div>
    </div>

    {{-- Filters --}}
    <div class="bg-white dark:bg-gray-900 rounded-xl p-4 border border-gray-200 dark:border-gray-800">
        <form action="{{ route('admin.ai.logs.index') }}" method="GET" class="flex flex-wrap items-end gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('ai.filter_by_status') }}</label>
                <select name="status" class="px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 focus:ring-2 focus:ring-violet-500">
                    <option value="">{{ __('admin.all') }}</option>
                    <option value="success" {{ request('status') == 'success' ? 'selected' : '' }}>{{ __('ai.status_success') }}</option>
                    <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>{{ __('ai.status_failed') }}</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>{{ __('ai.status_pending') }}</option>
                    <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>{{ __('ai.status_processing') }}</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('ai.filter_by_provider') }}</label>
                <select name="provider" class="px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 focus:ring-2 focus:ring-violet-500">
                    <option value="">{{ __('admin.all') }}</option>
                    <option value="openai" {{ request('provider') == 'openai' ? 'selected' : '' }}>OpenAI</option>
                    <option value="anthropic" {{ request('provider') == 'anthropic' ? 'selected' : '' }}>Anthropic</option>
                    <option value="google" {{ request('provider') == 'google' ? 'selected' : '' }}>Google</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('ai.date_from') }}</label>
                <input type="date" name="date_from" value="{{ request('date_from') }}"
                       class="px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 focus:ring-2 focus:ring-violet-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('ai.date_to') }}</label>
                <input type="date" name="date_to" value="{{ request('date_to') }}"
                       class="px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 focus:ring-2 focus:ring-violet-500">
            </div>
            <button type="submit" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 dark:bg-gray-800 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300 font-medium rounded-lg transition-colors">
                {{ __('admin.filter') }}
            </button>
            @if(request()->hasAny(['status', 'provider', 'date_from', 'date_to']))
                <a href="{{ route('admin.ai.logs.index') }}" class="px-4 py-2 text-gray-500 hover:text-gray-700 dark:hover:text-gray-300">
                    {{ __('ai.clear_filters') }}
                </a>
            @endif
        </form>
    </div>

    {{-- Logs Table --}}
    <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 dark:bg-gray-800">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('admin.created_at') }}</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('ai.topics') }}</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('ai.provider') }}</th>
                        <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('ai.status') }}</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('ai.tokens_used') }}</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('ai.cost') }}</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('admin.actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($logs as $log)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50">
                            <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-400">
                                {{ $log->created_at->format('M d, H:i') }}
                            </td>
                            <td class="px-4 py-3">
                                @if($log->topic)
                                    <span class="font-medium text-gray-900 dark:text-white">{{ $log->topic->name }}</span>
                                @else
                                    <span class="text-gray-400 italic">Deleted</span>
                                @endif
                                @if($log->generated_title)
                                    <p class="text-xs text-gray-500 dark:text-gray-400 truncate max-w-xs">{{ Str::limit($log->generated_title, 40) }}</p>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                <span class="inline-flex items-center px-2 py-1 text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300 rounded-full">
                                    {{ ucfirst($log->provider) }}
                                </span>
                                <span class="text-xs text-gray-500 block">{{ $log->model }}</span>
                            </td>
                            <td class="px-4 py-3 text-center">
                                @switch($log->status)
                                    @case('success')
                                        <span class="inline-flex items-center px-2 py-1 text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400 rounded-full">
                                            {{ __('ai.status_success') }}
                                        </span>
                                        @break
                                    @case('failed')
                                        <span class="inline-flex items-center px-2 py-1 text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400 rounded-full">
                                            {{ __('ai.status_failed') }}
                                        </span>
                                        @break
                                    @case('processing')
                                        <span class="inline-flex items-center px-2 py-1 text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400 rounded-full">
                                            {{ __('ai.status_processing') }}
                                        </span>
                                        @break
                                    @default
                                        <span class="inline-flex items-center px-2 py-1 text-xs font-medium bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-400 rounded-full">
                                            {{ __('ai.status_pending') }}
                                        </span>
                                @endswitch
                            </td>
                            <td class="px-4 py-3 text-right text-sm text-gray-600 dark:text-gray-400">
                                {{ $log->tokens_used ? number_format($log->tokens_used) : '-' }}
                            </td>
                            <td class="px-4 py-3 text-right text-sm text-gray-600 dark:text-gray-400">
                                {{ $log->cost ? '$' . number_format($log->cost, 4) : '-' }}
                            </td>
                            <td class="px-4 py-3 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    @if($log->post)
                                        <a href="{{ route('admin.posts.edit', $log->post) }}" class="p-2 text-blue-600 hover:bg-blue-50 dark:hover:bg-blue-900/20 rounded-lg transition-colors" title="{{ __('ai.view_post') }}">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                        </a>
                                    @endif
                                    @if($log->canRetry())
                                        <form action="{{ route('admin.ai.logs.retry', $log) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="p-2 text-amber-600 hover:bg-amber-50 dark:hover:bg-amber-900/20 rounded-lg transition-colors" title="{{ __('ai.retry') }}">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                                            </button>
                                        </form>
                                    @endif
                                    <a href="{{ route('admin.ai.logs.show', $log) }}" class="p-2 text-gray-600 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-800 rounded-lg transition-colors" title="{{ __('admin.view') }}">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-8 text-center text-gray-500 dark:text-gray-400">
                                {{ __('admin.no_items', ['items' => __('ai.logs')]) }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($logs->hasPages())
            <div class="px-4 py-3 border-t border-gray-200 dark:border-gray-700">
                {{ $logs->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
