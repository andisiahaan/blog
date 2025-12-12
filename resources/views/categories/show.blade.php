<x-layouts.main 
    :title="$category->name . ' - ' . setting('site_name', config('app.name'))"
    :description="$category->description ?? 'Posts in ' . $category->name">

<div class="space-y-8">
    <!-- Header -->
    <div class="mb-8">
        <nav class="text-sm text-gray-500 mb-3">
            <a href="{{ route('home') }}" class="hover:text-violet-600">{{ __('common.home') }}</a>
            <span class="mx-2">/</span>
            <span>{{ __('common.categories') }}</span>
        </nav>
        <h1 class="text-3xl md:text-4xl font-bold text-gray-900 dark:text-white mb-2">{{ $category->name }}</h1>
        @if($category->description)
            <p class="text-gray-600 dark:text-gray-400">{{ $category->description }}</p>
        @endif
        <p class="text-sm text-gray-500 mt-2">{{ $posts->total() }} {{ __('common.posts') }}</p>
    </div>

    <!-- Posts -->
    <div class="space-y-6">
        @forelse($posts as $post)
            <article class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 p-5 hover:border-violet-500/50 transition-colors">
                <div class="flex gap-4">
                    @if($post->featured_image)
                        <a href="{{ route('post.show', $post) }}" class="shrink-0">
                            <img src="{{ Storage::url($post->featured_image) }}" alt="" class="w-24 h-24 rounded-lg object-cover">
                        </a>
                    @endif
                    <div class="min-w-0 flex-1">
                        <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-1">
                            <a href="{{ route('post.show', $post) }}" class="hover:text-violet-600 dark:hover:text-violet-400 transition-colors">{{ $post->title }}</a>
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
            <div class="text-center py-12 text-gray-500">{{ __('common.no_posts') }}</div>
        @endforelse
    </div>

    @if($posts->hasPages())<div class="pt-4">{{ $posts->links() }}</div>@endif
</div>

</x-layouts.main>
