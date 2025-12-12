@extends('admin.layouts.app')
@section('title', __('admin.ad_settings'))
@section('header', __('admin.ad_settings'))

@section('content')
<form action="{{ route('admin.settings.ads.update') }}" method="POST" class="space-y-6">
    @csrf
    @method('PUT')

    {{-- Header Ads --}}
    <div class="bg-white dark:bg-gray-900 rounded-xl p-5 border border-gray-200 dark:border-gray-800">
        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-1">{{ __('admin.ad_header') }}</h3>
        <p class="text-sm text-gray-500 mb-4">{{ __('admin.ad_header_desc') }}</p>
        <textarea name="ad_header" rows="4" placeholder="<!-- Ad code here -->"
                  class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 focus:ring-2 focus:ring-violet-500 font-mono text-sm">{{ $settings['ad_header'] ?? '' }}</textarea>
    </div>

    {{-- Sidebar Ads --}}
    <div class="bg-white dark:bg-gray-900 rounded-xl p-5 border border-gray-200 dark:border-gray-800">
        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">{{ __('admin.ad_sidebar') }}</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('admin.ad_sidebar_top') }}</label>
                <textarea name="ad_sidebar_top" rows="4" placeholder="<!-- Ad code here -->"
                          class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 focus:ring-2 focus:ring-violet-500 font-mono text-sm">{{ $settings['ad_sidebar_top'] ?? '' }}</textarea>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('admin.ad_sidebar_bottom') }}</label>
                <textarea name="ad_sidebar_bottom" rows="4" placeholder="<!-- Ad code here -->"
                          class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 focus:ring-2 focus:ring-violet-500 font-mono text-sm">{{ $settings['ad_sidebar_bottom'] ?? '' }}</textarea>
            </div>
        </div>
    </div>

    {{-- Post Ads --}}
    <div class="bg-white dark:bg-gray-900 rounded-xl p-5 border border-gray-200 dark:border-gray-800">
        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">{{ __('admin.ad_post') }}</h3>
        <div class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('admin.ad_post_before_content') }}</label>
                <textarea name="ad_post_before_content" rows="3" placeholder="<!-- Ad code here -->"
                          class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 focus:ring-2 focus:ring-violet-500 font-mono text-sm">{{ $settings['ad_post_before_content'] ?? '' }}</textarea>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('admin.ad_post_in_content') }}</label>
                <p class="text-xs text-gray-500 mb-1">{{ __('admin.ad_post_in_content_desc') }}</p>
                <textarea name="ad_post_in_content" rows="3" placeholder="<!-- Ad code here -->"
                          class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 focus:ring-2 focus:ring-violet-500 font-mono text-sm">{{ $settings['ad_post_in_content'] ?? '' }}</textarea>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('admin.ad_post_after_content') }}</label>
                <textarea name="ad_post_after_content" rows="3" placeholder="<!-- Ad code here -->"
                          class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 focus:ring-2 focus:ring-violet-500 font-mono text-sm">{{ $settings['ad_post_after_content'] ?? '' }}</textarea>
            </div>
        </div>
    </div>

    {{-- Listing Ads --}}
    <div class="bg-white dark:bg-gray-900 rounded-xl p-5 border border-gray-200 dark:border-gray-800">
        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-1">{{ __('admin.ad_between_posts') }}</h3>
        <p class="text-sm text-gray-500 mb-4">{{ __('admin.ad_between_posts_desc') }}</p>
        <textarea name="ad_between_posts" rows="4" placeholder="<!-- Ad code here -->"
                  class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 focus:ring-2 focus:ring-violet-500 font-mono text-sm">{{ $settings['ad_between_posts'] ?? '' }}</textarea>
    </div>

    {{-- Footer & Popup Ads --}}
    <div class="bg-white dark:bg-gray-900 rounded-xl p-5 border border-gray-200 dark:border-gray-800">
        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">{{ __('admin.ad_other') }}</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('admin.ad_footer') }}</label>
                <textarea name="ad_footer" rows="4" placeholder="<!-- Ad code here -->"
                          class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 focus:ring-2 focus:ring-violet-500 font-mono text-sm">{{ $settings['ad_footer'] ?? '' }}</textarea>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('admin.ad_popup') }}</label>
                <textarea name="ad_popup" rows="4" placeholder="<!-- Popup ad code -->"
                          class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 focus:ring-2 focus:ring-violet-500 font-mono text-sm">{{ $settings['ad_popup'] ?? '' }}</textarea>
            </div>
        </div>
    </div>

    {{-- Submit --}}
    <div class="flex justify-end">
        <button type="submit" class="px-6 py-2 bg-violet-600 hover:bg-violet-700 text-white font-medium rounded-lg transition-colors">
            {{ __('admin.save') }}
        </button>
    </div>
</form>
@endsection
