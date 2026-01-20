@extends('admin.layouts.app')
@section('title', __('ai.create_topic'))
@section('header', __('ai.create_topic'))

@section('content')
<form action="{{ route('admin.ai.topics.store') }}" method="POST" class="space-y-6 max-w-4xl">
    @csrf

    <div class="bg-white dark:bg-gray-900 rounded-xl p-5 border border-gray-200 dark:border-gray-800">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            {{-- Topic Name --}}
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('ai.topic_name') }} <span class="text-red-500">*</span></label>
                <input type="text" name="name" value="{{ old('name') }}" required
                       class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 focus:ring-2 focus:ring-violet-500 @error('name') border-red-500 @enderror">
                @error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            {{-- Description --}}
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('ai.topic_description') }}</label>
                <textarea name="description" rows="3"
                          class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 focus:ring-2 focus:ring-violet-500">{{ old('description') }}</textarea>
            </div>

            {{-- Keywords --}}
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('ai.topic_keywords') }}</label>
                <input type="text" name="keywords" value="{{ old('keywords') }}"
                       class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 focus:ring-2 focus:ring-violet-500">
                <p class="text-xs text-gray-500 mt-1">{{ __('ai.topic_keywords_help') }}</p>
            </div>

            {{-- Tone --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('ai.topic_tone') }} <span class="text-red-500">*</span></label>
                <select name="tone" required class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 focus:ring-2 focus:ring-violet-500">
                    @foreach($tones as $key => $label)
                        <option value="{{ $key }}" {{ old('tone', 'professional') == $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Language --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('ai.topic_language') }} <span class="text-red-500">*</span></label>
                <select name="language" required class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 focus:ring-2 focus:ring-violet-500">
                    @foreach($languages as $key => $label)
                        <option value="{{ $key }}" {{ old('language', 'id') == $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Min Words --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('ai.min_words') }} <span class="text-red-500">*</span></label>
                <input type="number" name="min_words" value="{{ old('min_words', 800) }}" min="100" max="5000" required
                       class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 focus:ring-2 focus:ring-violet-500">
            </div>

            {{-- Max Words --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('ai.max_words') }} <span class="text-red-500">*</span></label>
                <input type="number" name="max_words" value="{{ old('max_words', 1500) }}" min="100" max="10000" required
                       class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 focus:ring-2 focus:ring-violet-500">
            </div>

            {{-- Category --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('ai.assign_category') }}</label>
                <select name="category_id" class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 focus:ring-2 focus:ring-violet-500">
                    <option value="">-- {{ __('admin.select_categories') }} --</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                    @endforeach
                </select>
                <p class="text-xs text-gray-500 mt-1">{{ __('ai.assign_category_help') }}</p>
            </div>

            {{-- Priority --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('ai.topic_priority') }}</label>
                <input type="number" name="priority" value="{{ old('priority', 0) }}" min="0" max="100"
                       class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 focus:ring-2 focus:ring-violet-500">
                <p class="text-xs text-gray-500 mt-1">{{ __('ai.topic_priority_help') }}</p>
            </div>

            {{-- Active --}}
            <div class="md:col-span-2">
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="hidden" name="is_active" value="0">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }} class="sr-only peer">
                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-violet-300 dark:peer-focus:ring-violet-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-violet-600"></div>
                    <span class="ml-3 text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('ai.topic_active') }}</span>
                </label>
            </div>
        </div>
    </div>

    {{-- Actions --}}
    <div class="flex items-center gap-4">
        <button type="submit" class="px-6 py-2 bg-violet-600 hover:bg-violet-700 text-white font-medium rounded-lg transition-colors">
            {{ __('admin.create') }}
        </button>
        <a href="{{ route('admin.ai.topics.index') }}" class="px-6 py-2 bg-gray-100 hover:bg-gray-200 dark:bg-gray-800 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300 font-medium rounded-lg transition-colors">
            {{ __('admin.cancel') }}
        </a>
    </div>
</form>
@endsection
