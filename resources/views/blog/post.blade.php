@extends('layouts.main')

@section('title', $post->title . ' - ' . config('app.name'))
@section('description', $post->excerpt)
@section('og_title', $post->title)
@section('og_description', $post->excerpt)
@section('og_type', 'article')
@if($post->featured_image)@section('og_image', Storage::url($post->featured_image))@endif

@section('content')
<article class="max-w-3xl">
    <!-- Header -->
    <header class="mb-8">
        @if($post->categories->count())
            <div class="flex flex-wrap gap-2 mb-4">
                @foreach($post->categories as $category)
                    <a href="{{ route('blog.category', $category) }}" class="px-3 py-1 text-sm font-medium rounded-full bg-violet-100 dark:bg-violet-900/30 text-violet-700 dark:text-violet-400 hover:bg-violet-200 dark:hover:bg-violet-900/50 transition-colors">{{ $category->name }}</a>
                @endforeach
            </div>
        @endif

        <h1 class="text-3xl md:text-4xl font-bold text-gray-900 dark:text-white mb-4">{{ $post->title }}</h1>

        <div class="flex items-center text-sm text-gray-500 dark:text-gray-400 space-x-4">
            @if($post->user)
                <div class="flex items-center">
                    <div class="w-8 h-8 rounded-full bg-gradient-to-br from-violet-600 to-purple-600 flex items-center justify-center text-white text-sm font-medium mr-2">{{ substr($post->user->name, 0, 1) }}</div>
                    <span>{{ $post->user->name }}</span>
                </div>
            @endif
            <span>·</span>
            <time datetime="{{ $post->published_at?->toISOString() }}">{{ $post->published_at?->format('F d, Y') }}</time>
            <span>·</span>
            <span>{{ number_format($post->views) }} {{ __('common.views') }}</span>
        </div>
    </header>

    <!-- Featured Image -->
    @if($post->featured_image)
        <figure class="mb-8 -mx-4 sm:mx-0">
            <img src="{{ Storage::url($post->featured_image) }}" alt="{{ $post->title }}" class="w-full rounded-none sm:rounded-xl object-cover max-h-[500px]">
        </figure>
    @endif

    <!-- Content -->
    <div class="prose prose-lg dark:prose-invert prose-violet max-w-none mb-8">
        {!! $post->content !!}
    </div>

    <!-- Tags -->
    @if($post->tags->count())
        <div class="flex flex-wrap gap-2 py-6 border-t border-gray-200 dark:border-gray-800">
            @foreach($post->tags as $tag)
                <a href="{{ route('blog.tag', $tag) }}" class="px-3 py-1 text-sm rounded-full bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-400 hover:bg-violet-100 dark:hover:bg-violet-900/30 hover:text-violet-600 dark:hover:text-violet-400 transition-colors">#{{ $tag->name }}</a>
            @endforeach
        </div>
    @endif

    <!-- Share -->
    <div class="py-6 border-t border-gray-200 dark:border-gray-800">
        <p class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">{{ __('common.share') }}</p>
        <div class="flex gap-2">
            <a href="https://twitter.com/intent/tweet?url={{ urlencode(request()->url()) }}&text={{ urlencode($post->title) }}" target="_blank" class="p-2 rounded-lg bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-400 hover:bg-blue-100 hover:text-blue-500 transition-colors">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/></svg>
            </a>
            <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(request()->url()) }}" target="_blank" class="p-2 rounded-lg bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-400 hover:bg-blue-100 hover:text-blue-600 transition-colors">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
            </a>
            <button onclick="navigator.clipboard.writeText('{{ request()->url() }}'); this.innerHTML = '{{ __('common.link_copied') }}'; setTimeout(() => this.innerHTML = '{{ __('common.copy_link') }}', 2000)" class="px-3 py-2 rounded-lg bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-400 text-sm hover:bg-violet-100 hover:text-violet-600 transition-colors">{{ __('common.copy_link') }}</button>
        </div>
    </div>
</article>

<!-- Related Posts -->
@if($relatedPosts->count())
<div class="mt-12 pt-8 border-t border-gray-200 dark:border-gray-800">
    <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-6">{{ __('common.related_posts') }}</h2>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        @foreach($relatedPosts as $related)
            <a href="{{ route('blog.post', $related) }}" class="flex gap-4 p-4 rounded-xl bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 hover:border-violet-500/50 transition-colors group">
                @if($related->featured_image)
                    <img src="{{ Storage::url($related->featured_image) }}" alt="" class="w-20 h-20 rounded-lg object-cover shrink-0">
                @endif
                <div>
                    <h3 class="font-medium text-gray-900 dark:text-white group-hover:text-violet-600 dark:group-hover:text-violet-400 line-clamp-2 transition-colors">{{ $related->title }}</h3>
                    <p class="text-sm text-gray-500 mt-1">{{ $related->published_at?->format('M d, Y') }}</p>
                </div>
            </a>
        @endforeach
    </div>
</div>
@endif
@endsection
