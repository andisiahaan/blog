<div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 p-5">
    <h3 class="font-semibold text-gray-900 dark:text-white mb-4">{{ __('common.random_posts') }}</h3>
    <div class="space-y-4">
        @foreach($posts as $post)
            <a href="{{ route('post.show', $post) }}" class="flex gap-3 group">
                @if($post->featured_image)
                    <img src="{{ Storage::url($post->featured_image) }}" alt="" class="w-16 h-16 rounded-lg object-cover shrink-0">
                @else
                    <div class="w-16 h-16 rounded-lg bg-gradient-to-br from-emerald-100 to-teal-100 dark:from-emerald-900/30 dark:to-teal-900/30 shrink-0 flex items-center justify-center">
                        <svg class="w-6 h-6 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path></svg>
                    </div>
                @endif
                <div class="min-w-0">
                    <h4 class="text-sm font-medium text-gray-900 dark:text-white group-hover:text-emerald-600 dark:group-hover:text-emerald-400 line-clamp-2 transition-colors">{{ $post->title }}</h4>
                    <p class="text-xs text-gray-500 mt-1">{{ $post->published_at?->format('M d, Y') }}</p>
                </div>
            </a>
        @endforeach
    </div>
</div>

