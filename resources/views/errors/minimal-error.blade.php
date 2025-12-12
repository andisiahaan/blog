@props(['code' => 500])
<x-layouts.main :title="__('errors.' . $code . '.title') ?: __('errors.title')">
    
    {{-- Error Content --}}
    <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 p-6 md:p-10">
        <div class="text-center">
            {{-- Error Code Badge --}}
            <div class="inline-flex items-center justify-center w-20 h-20 mb-6 rounded-full bg-gradient-to-br from-violet-500 to-purple-600 shadow-lg shadow-violet-500/30">
                <span class="text-2xl font-black text-white">{{ $code }}</span>
            </div>
            
            {{-- Error Title --}}
            <h1 class="text-3xl md:text-4xl font-bold text-gray-900 dark:text-white mb-3">
                {{ __("errors.{$code}.title") ?: __('errors.title') }}
            </h1>
            
            {{-- Error Message --}}
            <p class="text-gray-600 dark:text-gray-400 mb-8 max-w-lg mx-auto">
                {{ __("errors.{$code}.message") ?: __('errors.default_message') }}
            </p>
            
            {{-- Action Buttons --}}
            <div class="flex flex-col sm:flex-row gap-3 justify-center">
                <a href="{{ route('home') }}" 
                   class="inline-flex items-center justify-center px-6 py-3 bg-gradient-to-r from-violet-600 to-purple-600 hover:from-violet-700 hover:to-purple-700 text-white font-medium rounded-lg shadow-lg shadow-violet-500/30 hover:shadow-violet-500/50 transition-all duration-200">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                    </svg>
                    {{ __('common.back_to_home') }}
                </a>
                
                <button onclick="window.location.reload()" 
                        class="inline-flex items-center justify-center px-6 py-3 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 text-gray-700 dark:text-gray-300 font-medium rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-all duration-200">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                    </svg>
                    {{ __('common.reload') }}
                </button>
            </div>
        </div>
    </div>

    {{-- Quick Links --}}
    <div class="mt-6 bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 p-6">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 text-center">{{ __('common.quick_links') }}</h3>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
            <a href="{{ route('home') }}" class="flex items-center justify-center px-4 py-3 bg-gray-50 dark:bg-gray-800 rounded-lg text-sm text-gray-700 dark:text-gray-300 hover:bg-violet-50 dark:hover:bg-violet-900/20 hover:text-violet-600 dark:hover:text-violet-400 transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                </svg>
                {{ __('common.home') }}
            </a>
            
            <a href="{{ route('categories.index') }}" class="flex items-center justify-center px-4 py-3 bg-gray-50 dark:bg-gray-800 rounded-lg text-sm text-gray-700 dark:text-gray-300 hover:bg-violet-50 dark:hover:bg-violet-900/20 hover:text-violet-600 dark:hover:text-violet-400 transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                </svg>
                {{ __('common.categories') }}
            </a>
            
            <a href="{{ route('tags.index') }}" class="flex items-center justify-center px-4 py-3 bg-gray-50 dark:bg-gray-800 rounded-lg text-sm text-gray-700 dark:text-gray-300 hover:bg-violet-50 dark:hover:bg-violet-900/20 hover:text-violet-600 dark:hover:text-violet-400 transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A2 2 0 013 12V7a4 4 0 014-4z"></path>
                </svg>
                {{ __('common.tags') }}
            </a>
            
            <a href="{{ route('search') }}" class="flex items-center justify-center px-4 py-3 bg-gray-50 dark:bg-gray-800 rounded-lg text-sm text-gray-700 dark:text-gray-300 hover:bg-violet-50 dark:hover:bg-violet-900/20 hover:text-violet-600 dark:hover:text-violet-400 transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
                {{ __('common.search') }}
            </a>
        </div>
    </div>

</x-layouts.main>