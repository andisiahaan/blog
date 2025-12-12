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
