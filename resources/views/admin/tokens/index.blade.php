@extends('admin.layouts.app')
@section('title', __('admin.api_tokens'))
@section('header', __('admin.api_tokens'))

@section('content')
<div class="space-y-6">
    {{-- New Token Alert --}}
    @if(session('new_token'))
        <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-xl p-5" x-data="{ copied: false }">
            <div class="flex items-start gap-3">
                <svg class="w-5 h-5 text-green-500 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <div class="flex-1">
                    <h3 class="text-sm font-medium text-green-800 dark:text-green-200">{{ __('admin.token_created_success') }}</h3>
                    <p class="text-sm text-green-700 dark:text-green-300 mt-1">{{ __('admin.token_copy_warning') }}</p>
                    <div class="mt-3 flex items-center gap-2">
                        <code class="flex-1 px-3 py-2 bg-white dark:bg-gray-900 border border-green-200 dark:border-green-700 rounded-lg text-sm font-mono text-gray-900 dark:text-white break-all">{{ session('new_token') }}</code>
                        <button type="button" 
                                @click="navigator.clipboard.writeText('{{ session('new_token') }}'); copied = true; setTimeout(() => copied = false, 2000)"
                                class="px-3 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg transition-colors">
                            <span x-show="!copied">{{ __('admin.copy') }}</span>
                            <span x-show="copied">{{ __('admin.copied') }}</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- Create Token Form --}}
    <div class="bg-white dark:bg-gray-900 rounded-xl p-5 border border-gray-200 dark:border-gray-800">
        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">{{ __('admin.create_token') }}</h3>
        <form action="{{ route('admin.tokens.store') }}" method="POST" class="flex gap-3">
            @csrf
            <input type="text" name="name" placeholder="{{ __('admin.token_name_placeholder') }}" required
                   class="flex-1 px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 focus:ring-2 focus:ring-violet-500">
            <button type="submit" class="px-4 py-2 bg-violet-600 hover:bg-violet-700 text-white text-sm font-medium rounded-lg transition-colors">
                {{ __('admin.generate_token') }}
            </button>
        </form>
    </div>

    {{-- Tokens List --}}
    <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-200 dark:border-gray-800">
            <h3 class="text-lg font-medium text-gray-900 dark:text-white">{{ __('admin.existing_tokens') }}</h3>
        </div>
        
        @if($tokens->count())
            <div class="divide-y divide-gray-200 dark:divide-gray-800">
                @foreach($tokens as $token)
                    <div class="px-5 py-4 flex items-center justify-between">
                        <div>
                            <p class="font-medium text-gray-900 dark:text-white">{{ $token->name }}</p>
                            <p class="text-sm text-gray-500">
                                {{ __('admin.created') }}: {{ $token->created_at->format('M d, Y H:i') }}
                                @if($token->last_used_at)
                                    · {{ __('admin.last_used') }}: {{ $token->last_used_at->diffForHumans() }}
                                @endif
                            </p>
                        </div>
                        <form id="delete-token-{{ $token->id }}" action="{{ route('admin.tokens.destroy', $token->id) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    class="px-3 py-1.5 text-sm text-red-600 hover:text-red-700 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-colors">
                                {{ __('admin.revoke') }}
                            </button>
                        </form>
                    </div>
                @endforeach
            </div>
        @else
            <div class="px-5 py-8 text-center text-gray-500">
                {{ __('admin.no_tokens') }}
            </div>
        @endif
    </div>

    {{-- API Documentation --}}
    <div class="bg-white dark:bg-gray-900 rounded-xl p-5 border border-gray-200 dark:border-gray-800">
        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">{{ __('admin.api_usage') }}</h3>
        <div class="space-y-4 text-sm">
            <div>
                <p class="font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('admin.authentication_header') }}</p>
                <code class="block px-3 py-2 bg-gray-100 dark:bg-gray-800 rounded-lg font-mono text-gray-800 dark:text-gray-200">
                    Authorization: Bearer YOUR_TOKEN
                </code>
            </div>
            <div>
                <p class="font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('admin.available_endpoints') }}</p>
                <div class="space-y-2 font-mono text-xs">
                    <div class="flex gap-2">
                        <span class="px-2 py-1 bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400 rounded">POST</span>
                        <span class="text-gray-600 dark:text-gray-400">/api/posts</span>
                        <span class="text-gray-400">- Create post</span>
                    </div>
                    <div class="flex gap-2">
                        <span class="px-2 py-1 bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400 rounded">GET</span>
                        <span class="text-gray-600 dark:text-gray-400">/api/tags</span>
                        <span class="text-gray-400">- List all tags</span>
                    </div>
                    <div class="flex gap-2">
                        <span class="px-2 py-1 bg-yellow-100 dark:bg-yellow-900/30 text-yellow-700 dark:text-yellow-400 rounded">PUT</span>
                        <span class="text-gray-600 dark:text-gray-400">/api/tags/{`{id}`}</span>
                        <span class="text-gray-400">- Update tag</span>
                    </div>
                    <div class="flex gap-2">
                        <span class="px-2 py-1 bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400 rounded">GET</span>
                        <span class="text-gray-600 dark:text-gray-400">/api/categories</span>
                        <span class="text-gray-400">- List all categories</span>
                    </div>
                    <div class="flex gap-2">
                        <span class="px-2 py-1 bg-yellow-100 dark:bg-yellow-900/30 text-yellow-700 dark:text-yellow-400 rounded">PUT</span>
                        <span class="text-gray-600 dark:text-gray-400">/api/categories/{`{id}`}</span>
                        <span class="text-gray-400">- Update category</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
