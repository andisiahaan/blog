<x-layouts.main 
    :title="$post->title . ' - ' . setting('site_name', config('app.name'))"
    :description="$post->excerpt"
    :og-title="$post->title"
    :og-description="$post->excerpt"
    og-type="article"
    :og-image="$post->featured_image ? Storage::url($post->featured_image) : null">

@php
    // Prepare inline related posts - split content by paragraphs
    $content = $post->content;
    $inlineRelated = $relatedPosts->take(8);
    $bottomRelated = $relatedPosts->skip(8)->take(4);
    
    if ($bottomRelated->count() < 4 && $relatedPosts->count() > 0) {
        $bottomRelated = $relatedPosts->take(4);
    }
    
    $paragraphs = preg_split('/(<\/p>)/i', $content, -1, PREG_SPLIT_DELIM_CAPTURE);
    $processedContent = '';
    $realParagraphCount = 0;
    $inlineIndex = 0;
    
    for ($i = 0; $i < count($paragraphs); $i++) {
        $processedContent .= $paragraphs[$i];
        
        if (preg_match('/<\/p>/i', $paragraphs[$i])) {
            $prevParagraph = $i > 0 ? $paragraphs[$i - 1] : '';
            $strippedContent = trim(strip_tags($prevParagraph));
            
            if (strlen($strippedContent) > 20) {
                $realParagraphCount++;
                
                if ($realParagraphCount % 3 === 0 && $inlineIndex < $inlineRelated->count()) {
                    $post1 = $inlineRelated->get($inlineIndex);
                    $post2 = $inlineRelated->get($inlineIndex + 1);
                    $inlineIndex += 2;
                    
                    if ($post1) {
                        $processedContent .= '<div class="my-8 p-4 bg-gradient-to-r from-violet-50 to-purple-50 dark:from-violet-900/20 dark:to-purple-900/20 rounded-xl border border-violet-200 dark:border-violet-800/50">';
                        $processedContent .= '<p class="text-sm font-semibold text-violet-700 dark:text-violet-400 mb-3">' . __('common.read_also') . ':</p>';
                        $processedContent .= '<div class="space-y-2">';
                        
                        $processedContent .= '<a href="' . route('post.show', $post1) . '" class="flex items-center gap-2 text-gray-700 dark:text-gray-300 hover:text-violet-600 dark:hover:text-violet-400 transition-colors">';
                        $processedContent .= '<svg class="w-4 h-4 shrink-0 text-violet-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>';
                        $processedContent .= '<span class="line-clamp-1">' . e($post1->title) . '</span>';
                        $processedContent .= '</a>';
                        
                        if ($post2) {
                            $processedContent .= '<a href="' . route('post.show', $post2) . '" class="flex items-center gap-2 text-gray-700 dark:text-gray-300 hover:text-violet-600 dark:hover:text-violet-400 transition-colors">';
                            $processedContent .= '<svg class="w-4 h-4 shrink-0 text-violet-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>';
                            $processedContent .= '<span class="line-clamp-1">' . e($post2->title) . '</span>';
                            $processedContent .= '</a>';
                        }
                        
                        $processedContent .= '</div></div>';
                    }
                }
            }
        }
    }
@endphp

<article class="max-w-3xl">
    <!-- Header -->
    <header class="mb-8">
        @if($post->categories->count())
            <div class="flex flex-wrap gap-2 mb-4">
                @foreach($post->categories as $category)
                    <a href="{{ route('category.show', $category) }}" class="px-3 py-1 text-sm font-medium rounded-full bg-violet-100 dark:bg-violet-900/30 text-violet-700 dark:text-violet-400 hover:bg-violet-200 dark:hover:bg-violet-900/50 transition-colors">{{ $category->name }}</a>
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

    <!-- Content with Inline Related Posts -->
    <div class="prose prose-lg dark:prose-invert prose-violet max-w-none mb-8">
        {!! $processedContent !!}
    </div>

    <!-- Tags -->
    @if($post->tags->count())
        <div class="flex flex-wrap gap-2 py-6 border-t border-gray-200 dark:border-gray-800">
            @foreach($post->tags as $tag)
                <a href="{{ route('tag.show', $tag) }}" class="px-3 py-1 text-sm rounded-full bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-400 hover:bg-violet-100 dark:hover:bg-violet-900/30 hover:text-violet-600 dark:hover:text-violet-400 transition-colors">#{{ $tag->name }}</a>
            @endforeach
        </div>
    @endif

    <!-- Share Buttons -->
    <div class="py-6 border-t border-gray-200 dark:border-gray-800">
        <p class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">{{ __('common.share') }}</p>
        <div class="flex flex-wrap gap-2">
            <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(request()->url()) }}" target="_blank" rel="noopener" class="inline-flex items-center gap-2 px-3 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700 transition-colors">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                <span class="text-sm font-medium hidden sm:inline">Facebook</span>
            </a>
            
            <a href="https://wa.me/?text={{ urlencode($post->title . ' ' . request()->url()) }}" target="_blank" rel="noopener" class="inline-flex items-center gap-2 px-3 py-2 rounded-lg bg-green-500 text-white hover:bg-green-600 transition-colors">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                <span class="text-sm font-medium hidden sm:inline">{{ __('common.share_whatsapp') }}</span>
            </a>
            
            <a href="https://t.me/share/url?url={{ urlencode(request()->url()) }}&text={{ urlencode($post->title) }}" target="_blank" rel="noopener" class="inline-flex items-center gap-2 px-3 py-2 rounded-lg bg-sky-500 text-white hover:bg-sky-600 transition-colors">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M11.944 0A12 12 0 0 0 0 12a12 12 0 0 0 12 12 12 12 0 0 0 12-12A12 12 0 0 0 12 0a12 12 0 0 0-.056 0zm4.962 7.224c.1-.002.321.023.465.14a.506.506 0 0 1 .171.325c.016.093.036.306.02.472-.18 1.898-.962 6.502-1.36 8.627-.168.9-.499 1.201-.82 1.23-.696.065-1.225-.46-1.9-.902-1.056-.693-1.653-1.124-2.678-1.8-1.185-.78-.417-1.21.258-1.91.177-.184 3.247-2.977 3.307-3.23.007-.032.014-.15-.056-.212s-.174-.041-.249-.024c-.106.024-1.793 1.14-5.061 3.345-.48.33-.913.49-1.302.48-.428-.008-1.252-.241-1.865-.44-.752-.245-1.349-.374-1.297-.789.027-.216.325-.437.893-.663 3.498-1.524 5.83-2.529 6.998-3.014 3.332-1.386 4.025-1.627 4.476-1.635z"/></svg>
                <span class="text-sm font-medium hidden sm:inline">{{ __('common.share_telegram') }}</span>
            </a>
            
            <a href="https://twitter.com/intent/tweet?url={{ urlencode(request()->url()) }}&text={{ urlencode($post->title) }}" target="_blank" rel="noopener" class="inline-flex items-center gap-2 px-3 py-2 rounded-lg bg-black text-white hover:bg-gray-800 transition-colors">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                <span class="text-sm font-medium hidden sm:inline">X</span>
            </a>
            
            <button onclick="navigator.clipboard.writeText('{{ request()->url() }}'); this.querySelector('span').textContent = '{{ __('common.link_copied') }}'; setTimeout(() => this.querySelector('span').textContent = '{{ __('common.copy_link') }}', 2000)" class="inline-flex items-center gap-2 px-3 py-2 rounded-lg bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-400 hover:bg-violet-100 dark:hover:bg-violet-900/30 hover:text-violet-600 dark:hover:text-violet-400 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                <span class="text-sm font-medium">{{ __('common.copy_link') }}</span>
            </button>
            
            <button onclick="sharePost()" id="shareMoreBtn" style="display: none;" class="inline-flex items-center gap-2 px-3 py-2 rounded-lg bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-400 hover:bg-violet-100 dark:hover:bg-violet-900/30 hover:text-violet-600 dark:hover:text-violet-400 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"></path></svg>
                <span class="text-sm font-medium">{{ __('common.share_more') }}</span>
            </button>
        </div>
    </div>
</article>

<!-- Related Posts -->
@if($bottomRelated->count())
<div class="mt-12 pt-8 border-t border-gray-200 dark:border-gray-800">
    <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-6">{{ __('common.related_posts') }}</h2>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        @foreach($bottomRelated as $related)
            <a href="{{ route('post.show', $related) }}" class="flex gap-4 p-4 rounded-xl bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 hover:border-violet-500/50 transition-colors group">
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

<x-slot:scripts>
<script>
    if (navigator.share) {
        document.getElementById('shareMoreBtn').style.display = 'inline-flex';
    }
    
    async function sharePost() {
        try {
            await navigator.share({
                title: @json($post->title),
                text: @json($post->excerpt),
                url: '{{ request()->url() }}'
            });
        } catch (err) {
            console.log('Share cancelled or failed:', err);
        }
    }
</script>
</x-slot:scripts>

</x-layouts.main>
