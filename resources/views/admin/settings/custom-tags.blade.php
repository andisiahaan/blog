@extends('admin.layouts.app')
@section('title', __('admin.custom_tags'))
@section('header', __('admin.custom_tags'))

@section('content')
<form action="{{ route('admin.settings.custom-tags.update') }}" method="POST" class="space-y-6">
    @csrf
    @method('PUT')

    {{-- Head & Body Tags --}}
    <div class="bg-white dark:bg-gray-900 rounded-xl p-5 border border-gray-200 dark:border-gray-800">
        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">{{ __('admin.head_body_tags') }}</h3>
        <div class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('admin.head_tag') }}</label>
                <p class="text-xs text-gray-500 mb-2">{{ __('admin.head_tag_desc') }}</p>
                <textarea name="head_tag" rows="4" placeholder="<!-- Scripts, styles, meta tags -->"
                          class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 focus:ring-2 focus:ring-violet-500 font-mono text-sm">{{ $settings['head_tag'] ?? '' }}</textarea>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('admin.body_end_tag') }}</label>
                <p class="text-xs text-gray-500 mb-2">{{ __('admin.body_end_tag_desc') }}</p>
                <textarea name="body_end_tag" rows="4" placeholder="<!-- Scripts before </body> -->"
                          class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 focus:ring-2 focus:ring-violet-500 font-mono text-sm">{{ $settings['body_end_tag'] ?? '' }}</textarea>
            </div>
        </div>
    </div>

    {{-- Header Tag --}}
    <div class="bg-white dark:bg-gray-900 rounded-xl p-5 border border-gray-200 dark:border-gray-800">
        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-1">{{ __('admin.tag_header') }}</h3>
        <p class="text-sm text-gray-500 mb-4">{{ __('admin.tag_header_desc') }}</p>
        <textarea name="header" rows="4" placeholder="<!-- HTML/JS code -->"
                  class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 focus:ring-2 focus:ring-violet-500 font-mono text-sm">{{ $settings['header'] ?? '' }}</textarea>
    </div>

    {{-- Sidebar Tags --}}
    <div class="bg-white dark:bg-gray-900 rounded-xl p-5 border border-gray-200 dark:border-gray-800">
        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">{{ __('admin.tag_sidebar') }}</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('admin.tag_sidebar_top') }}</label>
                <textarea name="sidebar_top" rows="4" placeholder="<!-- HTML/JS code -->"
                          class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 focus:ring-2 focus:ring-violet-500 font-mono text-sm">{{ $settings['sidebar_top'] ?? '' }}</textarea>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('admin.tag_sidebar_bottom') }}</label>
                <textarea name="sidebar_bottom" rows="4" placeholder="<!-- HTML/JS code -->"
                          class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 focus:ring-2 focus:ring-violet-500 font-mono text-sm">{{ $settings['sidebar_bottom'] ?? '' }}</textarea>
            </div>
        </div>
    </div>

    {{-- Post Tags --}}
    <div class="bg-white dark:bg-gray-900 rounded-xl p-5 border border-gray-200 dark:border-gray-800">
        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">{{ __('admin.tag_post') }}</h3>
        <div class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('admin.tag_post_before_content') }}</label>
                <textarea name="post_before_content" rows="3" placeholder="<!-- HTML/JS code -->"
                          class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 focus:ring-2 focus:ring-violet-500 font-mono text-sm">{{ $settings['post_before_content'] ?? '' }}</textarea>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('admin.tag_post_in_content') }}</label>
                <p class="text-xs text-gray-500 mb-1">{{ __('admin.tag_post_in_content_desc') }}</p>
                <textarea name="post_in_content" rows="3" placeholder="<!-- HTML/JS code -->"
                          class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 focus:ring-2 focus:ring-violet-500 font-mono text-sm">{{ $settings['post_in_content'] ?? '' }}</textarea>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('admin.tag_post_after_content') }}</label>
                <textarea name="post_after_content" rows="3" placeholder="<!-- HTML/JS code -->"
                          class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 focus:ring-2 focus:ring-violet-500 font-mono text-sm">{{ $settings['post_after_content'] ?? '' }}</textarea>
            </div>
        </div>
    </div>

    {{-- Listing Tags --}}
    <div class="bg-white dark:bg-gray-900 rounded-xl p-5 border border-gray-200 dark:border-gray-800">
        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-1">{{ __('admin.tag_between_posts') }}</h3>
        <p class="text-sm text-gray-500 mb-4">{{ __('admin.tag_between_posts_desc') }}</p>
        <textarea name="between_posts" rows="4" placeholder="<!-- HTML/JS code -->"
                  class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 focus:ring-2 focus:ring-violet-500 font-mono text-sm">{{ $settings['between_posts'] ?? '' }}</textarea>
    </div>

    {{-- Footer & Popup Tags --}}
    <div class="bg-white dark:bg-gray-900 rounded-xl p-5 border border-gray-200 dark:border-gray-800">
        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">{{ __('admin.tag_other') }}</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('admin.tag_footer') }}</label>
                <textarea name="footer" rows="4" placeholder="<!-- HTML/JS code -->"
                          class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 focus:ring-2 focus:ring-violet-500 font-mono text-sm">{{ $settings['footer'] ?? '' }}</textarea>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('admin.tag_popup') }}</label>
                <textarea name="popup" rows="4" placeholder="<!-- Popup HTML/JS code -->"
                          class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 focus:ring-2 focus:ring-violet-500 font-mono text-sm">{{ $settings['popup'] ?? '' }}</textarea>
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
