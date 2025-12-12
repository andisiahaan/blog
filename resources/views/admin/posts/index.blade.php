@extends('admin.layouts.app')

@section('title', __('admin.posts'))
@section('header', __('admin.posts'))

@section('content')
<div class="space-y-4">
    <!-- Header Actions -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div class="flex-1">
            <form method="GET" class="flex flex-col sm:flex-row gap-2">
                <div class="relative flex-1 max-w-xs">
                    <input type="text" name="search" value="{{ request('search') }}" 
                           placeholder="{{ __('admin.search') }}..." 
                           class="w-full pl-10 pr-4 py-2 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-sm focus:ring-2 focus:ring-violet-500 focus:border-transparent">
                    <svg class="absolute left-3 top-2.5 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
                <select name="status" onchange="this.form.submit()" class="px-3 py-2 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-sm">
                    <option value="">{{ __('admin.all') }} {{ __('admin.status') }}</option>
                    <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>{{ __('admin.draft') }}</option>
                    <option value="published" {{ request('status') === 'published' ? 'selected' : '' }}>{{ __('admin.published') }}</option>
                    <option value="scheduled" {{ request('status') === 'scheduled' ? 'selected' : '' }}>{{ __('admin.scheduled') }}</option>
                </select>
                <select name="category" onchange="this.form.submit()" class="px-3 py-2 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-sm">
                    <option value="">{{ __('admin.all') }} {{ __('admin.categories') }}</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                    @endforeach
                </select>
            </form>
        </div>
        <a href="{{ route('admin.posts.create') }}" class="inline-flex items-center px-4 py-2 bg-violet-600 hover:bg-violet-700 text-white text-sm font-medium rounded-lg transition-colors">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            {{ __('admin.create') }}
        </a>
    </div>

    <!-- Table -->
    <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 dark:bg-gray-800 text-gray-600 dark:text-gray-400 text-left">
                    <tr>
                        <th class="px-4 py-3 font-medium">{{ __('admin.title') }}</th>
                        <th class="px-4 py-3 font-medium hidden md:table-cell">{{ __('admin.categories') }}</th>
                        <th class="px-4 py-3 font-medium">{{ __('admin.status') }}</th>
                        <th class="px-4 py-3 font-medium hidden lg:table-cell">{{ __('admin.created_at') }}</th>
                        <th class="px-4 py-3 font-medium text-right">{{ __('admin.actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-800">
                    @forelse($posts as $post)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50">
                            <td class="px-4 py-3">
                                <div>
                                    <a href="{{ route('admin.posts.edit', $post) }}" class="font-medium text-gray-900 dark:text-white hover:text-violet-600">{{ $post->title }}</a>
                                    @if($post->user)
                                        <p class="text-xs text-gray-500 mt-0.5">{{ __('common.by_author') }} {{ $post->user->name }}</p>
                                    @endif
                                </div>
                            </td>
                            <td class="px-4 py-3 hidden md:table-cell">
                                <div class="flex flex-wrap gap-1">
                                    @foreach($post->categories->take(2) as $category)
                                        <span class="px-2 py-0.5 text-xs bg-gray-100 dark:bg-gray-800 rounded">{{ $category->name }}</span>
                                    @endforeach
                                    @if($post->categories->count() > 2)
                                        <span class="text-xs text-gray-500">+{{ $post->categories->count() - 2 }}</span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-4 py-3">
                                <span class="px-2 py-1 text-xs rounded-full {{ 
                                    $post->status === 'published' ? 'bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400' : 
                                    ($post->status === 'scheduled' ? 'bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400' : 
                                    'bg-yellow-100 dark:bg-yellow-900/30 text-yellow-700 dark:text-yellow-400') 
                                }}">{{ ucfirst($post->status) }}</span>
                            </td>
                            <td class="px-4 py-3 hidden lg:table-cell text-gray-500">
                                {{ $post->created_at->format('M d, Y') }}
                            </td>
                            <td class="px-4 py-3 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('post.show', $post) }}" target="_blank" class="p-1.5 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300" title="{{ __('admin.view') }}">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                    </a>
                                    <a href="{{ route('admin.posts.edit', $post) }}" class="p-1.5 text-gray-400 hover:text-violet-600" title="{{ __('admin.edit') }}">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                    </a>
                                    <form action="{{ route('admin.posts.destroy', $post) }}" method="POST" class="inline" onsubmit="return confirm('{{ __('admin.confirm_delete') }}')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1.5 text-gray-400 hover:text-red-600" title="{{ __('admin.delete') }}">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-8 text-center text-gray-500">
                                {{ __('admin.no_items', ['items' => strtolower(__('admin.posts'))]) }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    @if($posts->hasPages())
        <div class="flex justify-center">
            {{ $posts->links() }}
        </div>
    @endif
</div>
@endsection
