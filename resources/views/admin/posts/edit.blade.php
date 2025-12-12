@extends('admin.layouts.app')

@section('title', __('admin.edit') . ' ' . __('admin.posts'))
@section('header', __('admin.edit') . ' ' . __('admin.posts'))

@section('content')
<form action="{{ route('admin.posts.update', $post) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
    @csrf
    @method('PUT')
    
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Title & Slug -->
            <div class="bg-white dark:bg-gray-900 rounded-xl p-5 border border-gray-200 dark:border-gray-800 space-y-4">
                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('admin.title') }} *</label>
                    <input type="text" name="title" id="title" value="{{ old('title', $post->title) }}" required
                           class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 focus:ring-2 focus:ring-violet-500 focus:border-transparent @error('title') border-red-500 @enderror">
                    @error('title')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="slug" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('admin.slug') }}</label>
                    <input type="text" name="slug" id="slug" value="{{ old('slug', $post->slug) }}"
                           class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 focus:ring-2 focus:ring-violet-500 focus:border-transparent @error('slug') border-red-500 @enderror">
                    @error('slug')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
            </div>

            <!-- Content -->
            <div class="bg-white dark:bg-gray-900 rounded-xl p-5 border border-gray-200 dark:border-gray-800">
                <label for="content" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('admin.content') }} *</label>
                <input id="content" type="hidden" name="content" value="{{ old('content', $post->content) }}">
                <trix-editor input="content" class="trix-content bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg min-h-[300px]"></trix-editor>
                @error('content')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>

            <!-- Excerpt -->
            <div class="bg-white dark:bg-gray-900 rounded-xl p-5 border border-gray-200 dark:border-gray-800">
                <label for="excerpt" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('admin.excerpt') }}</label>
                <textarea name="excerpt" id="excerpt" rows="3" 
                          class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 focus:ring-2 focus:ring-violet-500 focus:border-transparent">{{ old('excerpt', $post->getAttributes()['excerpt']) }}</textarea>
            </div>

            <!-- Metas -->
            @php
                $existingMetas = $post->metas->map(fn($m) => ['key' => $m->meta_key, 'value' => $m->meta_value])->toArray();
                if(empty($existingMetas)) $existingMetas = [['key' => '', 'value' => '']];
            @endphp
            <div class="bg-white dark:bg-gray-900 rounded-xl p-5 border border-gray-200 dark:border-gray-800" x-data="{ metas: {{ json_encode(old('metas', $existingMetas)) }} }">
                <div class="flex items-center justify-between mb-4">
                    <label class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('admin.metas') }}</label>
                    <button type="button" @click="metas.push({key: '', value: ''})" class="text-sm text-violet-600 hover:text-violet-700">+ {{ __('admin.add_meta') }}</button>
                </div>
                <template x-for="(meta, index) in metas" :key="index">
                    <div class="flex gap-2 mb-2">
                        <input type="text" :name="'metas[' + index + '][key]'" x-model="meta.key" placeholder="{{ __('admin.meta_key') }}"
                               class="flex-1 px-3 py-2 text-sm rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900">
                        <input type="text" :name="'metas[' + index + '][value]'" x-model="meta.value" placeholder="{{ __('admin.meta_value') }}"
                               class="flex-1 px-3 py-2 text-sm rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900">
                        <button type="button" @click="metas.splice(index, 1)" class="px-2 text-red-500 hover:text-red-700">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>
                </template>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Publish -->
            <div class="bg-white dark:bg-gray-900 rounded-xl p-5 border border-gray-200 dark:border-gray-800 space-y-4">
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('admin.status') }}</label>
                    <select name="status" id="status" class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900">
                        <option value="draft" {{ old('status', $post->status) === 'draft' ? 'selected' : '' }}>{{ __('admin.draft') }}</option>
                        <option value="published" {{ old('status', $post->status) === 'published' ? 'selected' : '' }}>{{ __('admin.published') }}</option>
                        <option value="scheduled" {{ old('status', $post->status) === 'scheduled' ? 'selected' : '' }}>{{ __('admin.scheduled') }}</option>
                    </select>
                </div>
                <div>
                    <label for="published_at" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('admin.published_at') }}</label>
                    <input type="datetime-local" name="published_at" id="published_at" 
                           value="{{ old('published_at', $post->published_at?->format('Y-m-d\TH:i')) }}"
                           class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900">
                </div>
                <div class="flex gap-2 pt-2">
                    <button type="submit" class="flex-1 py-2 px-4 bg-violet-600 hover:bg-violet-700 text-white text-sm font-medium rounded-lg transition-colors">
                        {{ __('admin.update') }}
                    </button>
                    <a href="{{ route('admin.posts.index') }}" class="py-2 px-4 bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300 text-sm font-medium rounded-lg transition-colors">
                        {{ __('admin.cancel') }}
                    </a>
                </div>
            </div>

            <!-- Featured Image -->
            <div class="bg-white dark:bg-gray-900 rounded-xl p-5 border border-gray-200 dark:border-gray-800">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('admin.featured_image') }}</label>
                @if($post->featured_image)
                    <div class="mb-2">
                        <img src="{{ Storage::url($post->featured_image) }}" alt="" class="w-full h-32 object-cover rounded-lg">
                    </div>
                @endif
                <input type="file" name="featured_image" accept="image/*"
                       class="w-full text-sm text-gray-500 file:mr-2 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-violet-50 dark:file:bg-violet-900/30 file:text-violet-700 dark:file:text-violet-400 hover:file:bg-violet-100">
            </div>

            <!-- Categories -->
            <div class="bg-white dark:bg-gray-900 rounded-xl p-5 border border-gray-200 dark:border-gray-800">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('admin.categories') }}</label>
                <div class="space-y-2 max-h-40 overflow-y-auto">
                    @foreach($categories as $category)
                        <label class="flex items-center">
                            <input type="checkbox" name="categories[]" value="{{ $category->id }}" 
                                   {{ in_array($category->id, old('categories', $post->categories->pluck('id')->toArray())) ? 'checked' : '' }}
                                   class="w-4 h-4 text-violet-600 bg-gray-100 dark:bg-gray-800 border-gray-300 dark:border-gray-600 rounded focus:ring-violet-500">
                            <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">{{ $category->name }}</span>
                        </label>
                    @endforeach
                </div>
            </div>

            <!-- Tags -->
            <div class="bg-white dark:bg-gray-900 rounded-xl p-5 border border-gray-200 dark:border-gray-800">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('admin.tags') }}</label>
                <div class="space-y-2 max-h-40 overflow-y-auto">
                    @foreach($tags as $tag)
                        <label class="flex items-center">
                            <input type="checkbox" name="tags[]" value="{{ $tag->id }}" 
                                   {{ in_array($tag->id, old('tags', $post->tags->pluck('id')->toArray())) ? 'checked' : '' }}
                                   class="w-4 h-4 text-violet-600 bg-gray-100 dark:bg-gray-800 border-gray-300 dark:border-gray-600 rounded focus:ring-violet-500">
                            <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">{{ $tag->name }}</span>
                        </label>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</form>
@endsection
