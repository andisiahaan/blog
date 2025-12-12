@extends('layouts.main')

@section('title', __('common.search_results') . ': ' . $query . ' - ' . config('app.name'))

@section('content')
<div class="space-y-8">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl md:text-4xl font-bold text-gray-900 dark:text-white mb-2">{{ __('common.search_results') }}</h1>
        <p class="text-gray-600 dark:text-gray-400">
            @if($query)
                {{ __('common.results_for') }} "<span class="font-medium text-violet-600 dark:text-violet-400">{{ $query }}</span>"
            @else
                {{ __('common.enter_search') }}
            @endif
        </p>
        @if($query)
            <p class="text-sm text-gray-500 mt-2">{{ $posts->total() }} {{ __('common.results_found') }}</p>
        @endif
    </div>

    <!-- Search Form -->
    <form action="{{ route('search') }}" method="GET" class="mb-6">
        <div class="relative max-w-xl">
            <svg class="absolute left-4 top-3.5 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            <input type="text" name="q" value="{{ $query }}" placeholder="{{ __('common.search_placeholder') }}" 
                   class="w-full pl-12 pr-4 py-3 rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 focus:ring-2 focus:ring-violet-500 focus:border-transparent">
        </div>
    </form>

    <!-- Results -->
    @if($query)
        <div class="space-y-6">
            @forelse($posts as $post)
                <article class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 p-5 hover:border-violet-500/50 transition-colors">
                    <div class="flex gap-4">
                        @if($post->featured_image)
                            <a href="{{ route('posts.show', $post) }}" class="shrink-0">
                                <img src="{{ Storage::url($post->featured_image) }}" alt="" class="w-24 h-24 rounded-lg object-cover">
                            </a>
                        @endif
                        <div class="min-w-0 flex-1">
                            @if($post->categories->count())
                                <div class="flex flex-wrap gap-1 mb-1">
                                    @foreach($post->categories->take(2) as $category)
                                        <a href="{{ route('category.show', $category) }}" class="text-xs text-violet-600 dark:text-violet-400 hover:underline">{{ $category->name }}</a>
                                    @endforeach
                                </div>
                            @endif
                            <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-1">
                                <a href="{{ route('posts.show', $post) }}" class="hover:text-violet-600 dark:hover:text-violet-400 transition-colors">{{ $post->title }}</a>
                            </h2>
                            <p class="text-sm text-gray-600 dark:text-gray-400 line-clamp-2 mb-2">{{ $post->excerpt }}</p>
                            <div class="flex items-center text-xs text-gray-500 space-x-3">
                                @if($post->user)<span>{{ $post->user->name }}</span><span>·</span>@endif
                                <time>{{ $post->published_at?->format('M d, Y') }}</time>
                            </div>
                        </div>
                    </div>
                </article>
            @empty
                <div class="text-center py-12">
                    <svg class="w-16 h-16 mx-auto text-gray-300 dark:text-gray-700 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    <p class="text-gray-500 dark:text-gray-400">{{ __('common.no_results') }}</p>
                </div>
            @endforelse
        </div>

        @if($posts->hasPages())<div class="pt-4">{{ $posts->links() }}</div>@endif
    @endif
</div>
@endsection
