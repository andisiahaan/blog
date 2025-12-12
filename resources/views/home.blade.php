<x-layouts.main 
    :title="setting('site_name', config('app.name')) . ' - ' . __('common.home')"
    :description="setting('site_description', __('common.latest_articles'))">

<div class="space-y-8">
    <!-- Welcome Section -->
    <div class="mb-8">
        <h1 class="text-3xl md:text-4xl font-bold text-gray-900 dark:text-white mb-2">{{ __('common.recent_posts') }}</h1>
        <p class="text-gray-600 dark:text-gray-400">{{ __('common.latest_articles') }}</p>
    </div>

    <!-- Posts Grid -->
    <div class="space-y-8">
        @forelse($posts as $post)
            <article class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 overflow-hidden hover:border-violet-500/50 transition-colors">
                <div class="md:flex">
                    @if($post->featured_image)
                        <a href="{{ route('post.show', $post) }}" class="block md:w-72 shrink-0">
                            <img src="{{ Storage::url($post->featured_image) }}" alt="{{ $post->title }}" class="w-full h-48 md:h-full object-cover">
                        </a>
                    @endif
                    <div class="p-5 flex flex-col justify-between flex-1">
                        <div>
                            <!-- Categories -->
                            @if($post->categories->count())
                                <div class="flex flex-wrap gap-2 mb-3">
                                    @foreach($post->categories->take(2) as $category)
                                        <a href="{{ route('category.show', $category) }}" class="text-xs font-medium text-violet-600 dark:text-violet-400 hover:underline">{{ $category->name }}</a>
                                    @endforeach
                                </div>
                            @endif

                            <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-2">
                                <a href="{{ route('post.show', $post) }}" class="hover:text-violet-600 dark:hover:text-violet-400 transition-colors">{{ $post->title }}</a>
                            </h2>
                            <p class="text-gray-600 dark:text-gray-400 text-sm line-clamp-2 mb-4">{{ $post->excerpt }}</p>
                        </div>

                        <div class="flex items-center justify-between text-sm text-gray-500">
                            <div class="flex items-center space-x-3">
                                @if($post->user)
                                    <span class="flex items-center">
                                        <div class="w-6 h-6 rounded-full bg-gradient-to-br from-violet-600 to-purple-600 flex items-center justify-center text-white text-xs font-medium mr-2">{{ substr($post->user->name, 0, 1) }}</div>
                                        {{ $post->user->name }}
                                    </span>
                                @endif
                                <span>·</span>
                                <time datetime="{{ $post->published_at?->toISOString() }}">{{ $post->published_at?->format('M d, Y') }}</time>
                            </div>
                            <a href="{{ route('post.show', $post) }}" class="font-medium text-violet-600 dark:text-violet-400 hover:underline">{{ __('common.read_more') }} &rarr;</a>
                        </div>
                    </div>
                </div>
            </article>
        @empty
            <div class="text-center py-12">
                <svg class="w-16 h-16 mx-auto text-gray-300 dark:text-gray-700 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path></svg>
                <p class="text-gray-500 dark:text-gray-400">{{ __('common.no_posts') }}</p>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($posts->hasPages())
        <div class="pt-4">
            {{ $posts->links() }}
        </div>
    @endif
</div>

</x-layouts.main>
