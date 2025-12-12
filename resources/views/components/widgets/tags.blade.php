<div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 p-5">
    <div class="flex items-center justify-between mb-4">
        <h3 class="font-semibold text-gray-900 dark:text-white">{{ __('common.tags') }}</h3>
        <a href="{{ route('tags.index') }}" class="text-xs text-violet-600 dark:text-violet-400 hover:underline">{{ __('common.view_all') }}</a>
    </div>
    <div class="flex flex-wrap gap-2">
        @foreach($tags as $tag)
            <a href="{{ route('tag.show', $tag) }}" class="px-2 py-1 text-xs rounded bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-400 hover:bg-violet-100 dark:hover:bg-violet-900/30 hover:text-violet-600 dark:hover:text-violet-400 transition-colors">
                #{{ $tag->name }}
            </a>
        @endforeach
    </div>
</div>

