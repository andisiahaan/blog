@extends('admin.layouts.app')
@section('title', __('admin.create') . ' ' . __('admin.pages'))
@section('header', __('admin.create') . ' ' . __('admin.pages'))
@section('content')
<form action="{{ route('admin.pages.store') }}" method="POST" class="space-y-6">
    @csrf
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white dark:bg-gray-900 rounded-xl p-5 border border-gray-200 dark:border-gray-800 space-y-4">
                <div><label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('admin.title') }} *</label><input type="text" name="title" id="title" value="{{ old('title') }}" required class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 focus:ring-2 focus:ring-violet-500"></div>
                <div><label for="slug" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('admin.slug') }}</label><input type="text" name="slug" id="slug" value="{{ old('slug') }}" placeholder="{{ __('admin.auto_generate') }}" class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 focus:ring-2 focus:ring-violet-500"></div>
            </div>
            <div class="bg-white dark:bg-gray-900 rounded-xl p-5 border border-gray-200 dark:border-gray-800">
                <label for="content" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('admin.content') }} *</label>
                <input id="content" type="hidden" name="content" value="{{ old('content') }}">
                <trix-editor input="content" class="trix-content bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg min-h-[300px]"></trix-editor>
            </div>
            <div class="bg-white dark:bg-gray-900 rounded-xl p-5 border border-gray-200 dark:border-gray-800" x-data="{ metas: {{ json_encode(old('metas', [['key' => '', 'value' => '']])) }} }">
                <div class="flex items-center justify-between mb-4"><label class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('admin.metas') }}</label><button type="button" @click="metas.push({key: '', value: ''})" class="text-sm text-violet-600">+ {{ __('admin.add_meta') }}</button></div>
                <template x-for="(meta, index) in metas" :key="index"><div class="flex gap-2 mb-2"><input type="text" :name="'metas[' + index + '][key]'" x-model="meta.key" placeholder="{{ __('admin.meta_key') }}" class="flex-1 px-3 py-2 text-sm rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900"><input type="text" :name="'metas[' + index + '][value]'" x-model="meta.value" placeholder="{{ __('admin.meta_value') }}" class="flex-1 px-3 py-2 text-sm rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900"><button type="button" @click="metas.splice(index, 1)" class="px-2 text-red-500"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button></div></template>
            </div>
        </div>
        <div class="space-y-6">
            <div class="bg-white dark:bg-gray-900 rounded-xl p-5 border border-gray-200 dark:border-gray-800 space-y-4">
                <div class="flex items-center"><input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }} class="w-4 h-4 text-violet-600 rounded"><label for="is_active" class="ml-2 text-sm text-gray-700 dark:text-gray-300">{{ __('admin.active') }}</label></div>
                <div><label for="order" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('admin.order') }}</label><input type="number" name="order" id="order" value="{{ old('order', 0) }}" class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900"></div>
                <div class="flex gap-2 pt-2"><button type="submit" class="flex-1 py-2 px-4 bg-violet-600 hover:bg-violet-700 text-white text-sm font-medium rounded-lg">{{ __('admin.save') }}</button><a href="{{ route('admin.pages.index') }}" class="py-2 px-4 bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 text-sm font-medium rounded-lg">{{ __('admin.cancel') }}</a></div>
            </div>
        </div>
    </div>
</form>
@endsection
