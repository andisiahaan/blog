<x-layouts.main 
    :title="__('common.all_categories_title') . ' - ' . setting('site_name', config('app.name'))"
    :description="__('common.all_categories_title')">

<div class="max-w-4xl">
    <header class="mb-8">
        <h1 class="text-3xl md:text-4xl font-bold text-gray-900 dark:text-white mb-4">{{ __('common.all_categories_title') }}</h1>
        
        <!-- Sort Options -->
        <div class="flex items-center gap-4 text-sm">
            <span class="text-gray-500 dark:text-gray-400">{{ __('common.sorted_by') }}:</span>
            <a href="{{ route('categories.index', ['sort' => 'name']) }}" 
               class="px-3 py-1.5 rounded-lg {{ $sort === 'name' ? 'bg-violet-600 text-white' : 'bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-400 hover:bg-violet-100 dark:hover:bg-violet-900/30' }} transition-colors">
                {{ __('common.name') }}
            </a>
            <a href="{{ route('categories.index', ['sort' => 'posts']) }}" 
               class="px-3 py-1.5 rounded-lg {{ $sort === 'posts' ? 'bg-violet-600 text-white' : 'bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-400 hover:bg-violet-100 dark:hover:bg-violet-900/30' }} transition-colors">
                {{ __('common.post_count') }}
            </a>
        </div>
    </header>

    <!-- Categories Grid -->
    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3">
        @forelse($categories as $category)
            <a href="{{ route('category.show', $category) }}" 
               class="flex flex-col p-4 rounded-xl bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 hover:border-violet-500/50 hover:shadow-lg transition-all group">
                <span class="font-medium text-gray-900 dark:text-white group-hover:text-violet-600 dark:group-hover:text-violet-400 transition-colors line-clamp-2">{{ $category->name }}</span>
                <span class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ number_format($category->posts_count) }} {{ __('common.posts') }}</span>
            </a>
        @empty
            <div class="col-span-full text-center py-12 text-gray-500 dark:text-gray-400">
                {{ __('common.no_results') }}
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($categories->hasPages())
    <div class="mt-8">
        {{ $categories->withQueryString()->links() }}
    </div>
    @endif
</div>

</x-layouts.main>
