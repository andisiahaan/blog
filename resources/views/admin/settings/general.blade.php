@extends('admin.layouts.app')
@section('title', __('admin.general_settings'))
@section('header', __('admin.general_settings'))

@section('content')
<form action="{{ route('admin.settings.general.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
    @csrf
    @method('PUT')

    {{-- Site Identity --}}
    <div class="bg-white dark:bg-gray-900 rounded-xl p-5 border border-gray-200 dark:border-gray-800">
        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">{{ __('admin.site_identity') }}</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            {{-- Site Name --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('admin.site_name') }}</label>
                <input type="text" name="site_name" value="{{ $settings['site_name'] ?? config('app.name') }}"
                       class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 focus:ring-2 focus:ring-violet-500">
            </div>

            {{-- Site Email --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('admin.site_email') }}</label>
                <input type="email" name="site_email" value="{{ $settings['site_email'] ?? '' }}"
                       class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 focus:ring-2 focus:ring-violet-500">
            </div>

            {{-- Site Description --}}
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('admin.site_description') }}</label>
                <textarea name="site_description" rows="2"
                          class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 focus:ring-2 focus:ring-violet-500">{{ $settings['site_description'] ?? '' }}</textarea>
            </div>

            {{-- Site Keywords --}}
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('admin.site_keywords') }}</label>
                <input type="text" name="site_keywords" value="{{ $settings['site_keywords'] ?? '' }}"
                       class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 focus:ring-2 focus:ring-violet-500">
                <p class="text-xs text-gray-500 mt-1">{{ __('admin.keywords_hint') }}</p>
            </div>
        </div>
    </div>

    {{-- Logo & Favicon --}}
    <div class="bg-white dark:bg-gray-900 rounded-xl p-5 border border-gray-200 dark:border-gray-800">
        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">{{ __('admin.logo_favicon') }}</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            {{-- Logo --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('admin.site_logo') }}</label>
                @if(!empty($settings['site_logo']))
                    <div class="mb-2">
                        <img src="{{ asset('storage/' . $settings['site_logo']) }}" alt="Logo" class="h-12 object-contain">
                    </div>
                @endif
                <input type="file" name="site_logo" accept="image/*"
                       class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-violet-50 file:text-violet-700 hover:file:bg-violet-100 dark:file:bg-violet-900/30 dark:file:text-violet-400">
            </div>

            {{-- Favicon --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('admin.site_favicon') }}</label>
                @if(!empty($settings['site_favicon']))
                    <div class="mb-2">
                        <img src="{{ asset('storage/' . $settings['site_favicon']) }}" alt="Favicon" class="h-8 w-8 object-contain">
                    </div>
                @endif
                <input type="file" name="site_favicon" accept="image/*"
                       class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-violet-50 file:text-violet-700 hover:file:bg-violet-100 dark:file:bg-violet-900/30 dark:file:text-violet-400">
            </div>
        </div>
    </div>

    {{-- Display Settings --}}
    <div class="bg-white dark:bg-gray-900 rounded-xl p-5 border border-gray-200 dark:border-gray-800">
        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">{{ __('admin.display_settings') }}</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            {{-- Posts Per Page --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('admin.posts_per_page') }}</label>
                <input type="number" name="posts_per_page" value="{{ $settings['posts_per_page'] ?? 10 }}" min="1" max="50"
                       class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 focus:ring-2 focus:ring-violet-500">
            </div>

            {{-- Related Posts Limit --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('admin.related_posts_limit') }}</label>
                <input type="number" name="related_posts_limit" value="{{ $settings['related_posts_limit'] ?? 12 }}" min="0" max="20"
                       class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 focus:ring-2 focus:ring-violet-500">
            </div>
        </div>

        {{-- Sidebar Settings --}}
        <h4 class="text-md font-medium text-gray-700 dark:text-gray-300 mt-6 mb-3">{{ __('admin.sidebar_settings') }}</h4>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            {{-- Popular Posts --}}
            <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-800 rounded-lg">
                <div class="flex items-center gap-3">
                    <input type="hidden" name="sidebar_popular_enabled" value="0">
                    <input type="checkbox" name="sidebar_popular_enabled" value="1" {{ ($settings['sidebar_popular_enabled'] ?? true) ? 'checked' : '' }}
                           class="w-4 h-4 text-violet-600 rounded focus:ring-violet-500">
                    <label class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('admin.sidebar_popular_enabled') }}</label>
                </div>
                <input type="number" name="sidebar_popular_limit" value="{{ $settings['sidebar_popular_limit'] ?? 5 }}" min="1" max="20"
                       class="w-16 px-2 py-1 text-sm rounded border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900">
            </div>

            {{-- Random Posts --}}
            <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-800 rounded-lg">
                <div class="flex items-center gap-3">
                    <input type="hidden" name="sidebar_random_enabled" value="0">
                    <input type="checkbox" name="sidebar_random_enabled" value="1" {{ ($settings['sidebar_random_enabled'] ?? true) ? 'checked' : '' }}
                           class="w-4 h-4 text-violet-600 rounded focus:ring-violet-500">
                    <label class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('admin.sidebar_random_enabled') }}</label>
                </div>
                <input type="number" name="sidebar_random_limit" value="{{ $settings['sidebar_random_limit'] ?? 5 }}" min="1" max="20"
                       class="w-16 px-2 py-1 text-sm rounded border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900">
            </div>

            {{-- Categories --}}
            <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-800 rounded-lg">
                <div class="flex items-center gap-3">
                    <input type="hidden" name="sidebar_categories_enabled" value="0">
                    <input type="checkbox" name="sidebar_categories_enabled" value="1" {{ ($settings['sidebar_categories_enabled'] ?? true) ? 'checked' : '' }}
                           class="w-4 h-4 text-violet-600 rounded focus:ring-violet-500">
                    <label class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('admin.sidebar_categories_enabled') }}</label>
                </div>
                <input type="number" name="sidebar_categories_limit" value="{{ $settings['sidebar_categories_limit'] ?? 20 }}" min="1" max="50"
                       class="w-16 px-2 py-1 text-sm rounded border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900">
            </div>

            {{-- Tags --}}
            <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-800 rounded-lg">
                <div class="flex items-center gap-3">
                    <input type="hidden" name="sidebar_tags_enabled" value="0">
                    <input type="checkbox" name="sidebar_tags_enabled" value="1" {{ ($settings['sidebar_tags_enabled'] ?? true) ? 'checked' : '' }}
                           class="w-4 h-4 text-violet-600 rounded focus:ring-violet-500">
                    <label class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('admin.sidebar_tags_enabled') }}</label>
                </div>
                <input type="number" name="sidebar_tags_limit" value="{{ $settings['sidebar_tags_limit'] ?? 20 }}" min="1" max="50"
                       class="w-16 px-2 py-1 text-sm rounded border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900">
            </div>
        </div>
    </div>

    {{-- Footer & Analytics --}}
    <div class="bg-white dark:bg-gray-900 rounded-xl p-5 border border-gray-200 dark:border-gray-800">
        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">{{ __('admin.footer_analytics') }}</h3>
        <div class="space-y-4">
            {{-- Footer Text --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('admin.footer_text') }}</label>
                <textarea name="footer_text" rows="2"
                          class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 focus:ring-2 focus:ring-violet-500">{{ $settings['footer_text'] ?? '' }}</textarea>
            </div>

            {{-- Google Analytics --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('admin.google_analytics') }}</label>
                <textarea name="google_analytics" rows="4" placeholder="<!-- Google Analytics code -->"
                          class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 focus:ring-2 focus:ring-violet-500 font-mono text-sm">{{ $settings['google_analytics'] ?? '' }}</textarea>
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

