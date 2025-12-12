<x-layouts.main 
    :title="__('common.all_tags_title') . ' - ' . setting('site_name', config('app.name'))"
    :description="__('common.all_tags_title')">

<div class="max-w-4xl">
    <header class="mb-8">
        <h1 class="text-3xl md:text-4xl font-bold text-gray-900 dark:text-white mb-4">{{ __('common.all_tags_title') }}</h1>
        
        <!-- Sort Options -->
        <div class="flex items-center gap-4 text-sm">
            <span class="text-gray-500 dark:text-gray-400">{{ __('common.sorted_by') }}:</span>
            <a href="{{ route('tags.index', ['sort' => 'name']) }}" 
               class="px-3 py-1.5 rounded-lg {{ $sort === 'name' ? 'bg-violet-600 text-white' : 'bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-400 hover:bg-violet-100 dark:hover:bg-violet-900/30' }} transition-colors">
                {{ __('common.name') }}
            </a>
            <a href="{{ route('tags.index', ['sort' => 'posts']) }}" 
               class="px-3 py-1.5 rounded-lg {{ $sort === 'posts' ? 'bg-violet-600 text-white' : 'bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-400 hover:bg-violet-100 dark:hover:bg-violet-900/30' }} transition-colors">
                {{ __('common.post_count') }}
            </a>
        </div>
    </header>

    <!-- Tags Grid -->
    <div class="flex flex-wrap gap-2">
        @forelse($tags as $tag)
            <a href="{{ route('tag.show', $tag) }}" 
               class="px-3 py-2 rounded-lg bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-400 hover:bg-violet-100 dark:hover:bg-violet-900/30 hover:text-violet-600 dark:hover:text-violet-400 transition-colors">
                #{{ $tag->name }} <span class="text-xs text-gray-400 ml-1">({{ number_format($tag->posts_count) }})</span>
            </a>
        @empty
            <div class="w-full text-center py-12 text-gray-500 dark:text-gray-400">
                {{ __('common.no_results') }}
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($tags->hasPages())
    <div class="mt-8">
        {{ $tags->withQueryString()->links() }}
    </div>
    @endif
</div>

</x-layouts.main>
