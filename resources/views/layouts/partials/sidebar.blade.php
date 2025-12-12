<!-- Right Sidebar -->
<aside class="w-full lg:w-80 shrink-0 space-y-6">
    <!-- Popular Posts -->
    @isset($popularPosts)
    @if($popularPosts->count())
    <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 p-5">
        <h3 class="font-semibold text-gray-900 dark:text-white mb-4">{{ __('common.popular_posts') }}</h3>
        <div class="space-y-4">
            @foreach($popularPosts as $popular)
                <a href="{{ route('posts.show', $popular) }}" class="flex gap-3 group">
                    @if($popular->featured_image)
                        <img src="{{ Storage::url($popular->featured_image) }}" alt="" class="w-16 h-16 rounded-lg object-cover shrink-0">
                    @else
                        <div class="w-16 h-16 rounded-lg bg-gradient-to-br from-violet-100 to-purple-100 dark:from-violet-900/30 dark:to-purple-900/30 shrink-0 flex items-center justify-center">
                            <svg class="w-6 h-6 text-violet-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path></svg>
                        </div>
                    @endif
                    <div class="min-w-0">
                        <h4 class="text-sm font-medium text-gray-900 dark:text-white group-hover:text-violet-600 dark:group-hover:text-violet-400 line-clamp-2 transition-colors">{{ $popular->title }}</h4>
                        <p class="text-xs text-gray-500 mt-1">{{ number_format($popular->views) }} {{ __('common.views') }}</p>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
    @endif
    @endisset

    <!-- Categories -->
    @isset($categories)
    @if($categories->count())
    <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 p-5">
        <h3 class="font-semibold text-gray-900 dark:text-white mb-4">{{ __('common.categories') }}</h3>
        <div class="flex flex-wrap gap-2">
            @foreach($categories as $category)
                <a href="{{ route('category.show', $category) }}" class="px-3 py-1.5 text-sm rounded-full border border-gray-200 dark:border-gray-700 text-gray-600 dark:text-gray-400 hover:border-violet-500 hover:text-violet-600 dark:hover:text-violet-400 transition-colors">
                    {{ $category->name }} <span class="text-xs text-gray-400">({{ $category->posts_count }})</span>
                </a>
            @endforeach
        </div>
    </div>
    @endif
    @endisset

    <!-- Tags -->
    @isset($tags)
    @if($tags->count())
    <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 p-5">
        <h3 class="font-semibold text-gray-900 dark:text-white mb-4">{{ __('common.tags') }}</h3>
        <div class="flex flex-wrap gap-2">
            @foreach($tags->take(15) as $tag)
                <a href="{{ route('tag.show', $tag) }}" class="px-2 py-1 text-xs rounded bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-400 hover:bg-violet-100 dark:hover:bg-violet-900/30 hover:text-violet-600 dark:hover:text-violet-400 transition-colors">
                    #{{ $tag->name }}
                </a>
            @endforeach
        </div>
    </div>
    @endif
    @endisset
</aside>
