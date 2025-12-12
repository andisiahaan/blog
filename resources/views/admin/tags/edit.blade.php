@extends('admin.layouts.app')
@section('title', __('admin.edit') . ' ' . __('admin.tags'))
@section('header', __('admin.edit') . ' ' . __('admin.tags'))
@section('content')
@php $existingMetas = $tag->metas->map(fn($m) => ['key' => $m->meta_key, 'value' => $m->meta_value])->toArray(); if(empty($existingMetas)) $existingMetas = [['key' => '', 'value' => '']]; @endphp
<form action="{{ route('admin.tags.update', $tag) }}" method="POST" class="max-w-2xl space-y-6">
    @csrf @method('PUT')
    <div class="bg-white dark:bg-gray-900 rounded-xl p-5 border border-gray-200 dark:border-gray-800 space-y-4">
        <div>
            <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('admin.name') }} *</label>
            <input type="text" name="name" id="name" value="{{ old('name', $tag->name) }}" required class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 focus:ring-2 focus:ring-violet-500">
        </div>
        <div>
            <label for="slug" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('admin.slug') }}</label>
            <input type="text" name="slug" id="slug" value="{{ old('slug', $tag->slug) }}" class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 focus:ring-2 focus:ring-violet-500">
        </div>
        <div>
            <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('admin.description') }}</label>
            <textarea name="description" id="description" rows="3" class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 focus:ring-2 focus:ring-violet-500">{{ old('description', $tag->description) }}</textarea>
        </div>
    </div>
    <div class="bg-white dark:bg-gray-900 rounded-xl p-5 border border-gray-200 dark:border-gray-800" x-data="{ metas: {{ json_encode(old('metas', $existingMetas)) }} }">
        <div class="flex items-center justify-between mb-4"><label class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('admin.metas') }}</label><button type="button" @click="metas.push({key: '', value: ''})" class="text-sm text-violet-600 hover:text-violet-700">+ {{ __('admin.add_meta') }}</button></div>
        <template x-for="(meta, index) in metas" :key="index"><div class="flex gap-2 mb-2"><input type="text" :name="'metas[' + index + '][key]'" x-model="meta.key" placeholder="{{ __('admin.meta_key') }}" class="flex-1 px-3 py-2 text-sm rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900"><input type="text" :name="'metas[' + index + '][value]'" x-model="meta.value" placeholder="{{ __('admin.meta_value') }}" class="flex-1 px-3 py-2 text-sm rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900"><button type="button" @click="metas.splice(index, 1)" class="px-2 text-red-500 hover:text-red-700"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button></div></template>
    </div>
    <div class="flex gap-2"><button type="submit" class="py-2 px-6 bg-violet-600 hover:bg-violet-700 text-white text-sm font-medium rounded-lg">{{ __('admin.update') }}</button><a href="{{ route('admin.tags.index') }}" class="py-2 px-6 bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 text-sm font-medium rounded-lg">{{ __('admin.cancel') }}</a></div>
</form>
@endsection
