<div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 p-5">
    <div class="flex items-center justify-between mb-4">
        <h3 class="font-semibold text-gray-900 dark:text-white">{{ __('common.categories') }}</h3>
        <a href="{{ route('categories.index') }}" class="text-xs text-violet-600 dark:text-violet-400 hover:underline">{{ __('common.view_all') }}</a>
    </div>
    <div class="flex flex-wrap gap-2">
        @foreach($categories as $category)
            <a href="{{ route('category.show', $category) }}" class="px-3 py-1.5 text-sm rounded-full border border-gray-200 dark:border-gray-700 text-gray-600 dark:text-gray-400 hover:border-violet-500 hover:text-violet-600 dark:hover:text-violet-400 transition-colors">
                {{ $category->name }} <span class="text-xs text-gray-400">({{ $category->posts_count }})</span>
            </a>
        @endforeach
    </div>
</div>

