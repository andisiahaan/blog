<!-- Footer -->
<footer class="mt-16 border-t border-gray-200 dark:border-gray-800 bg-gray-50 dark:bg-gray-900/50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="flex flex-col md:flex-row items-center justify-between gap-4">
            <!-- Logo & Copyright -->
            <div class="flex items-center space-x-2">
                <div class="w-6 h-6 bg-gradient-to-br from-violet-600 to-purple-600 rounded flex items-center justify-center">
                    <span class="text-white font-bold text-xs">B</span>
                </div>
                <span class="text-sm text-gray-600 dark:text-gray-400">
                    &copy; {{ date('Y') }} {{ config('app.name') }}. {{ __('common.footer_text') }}
                </span>
            </div>

            <!-- Links -->
            <div class="flex items-center space-x-6">
                @isset($pages)
                    @foreach($pages->take(3) as $page)
                        <a href="{{ route('pages.show', $page) }}" class="text-sm text-gray-600 dark:text-gray-400 hover:text-violet-600 dark:hover:text-violet-400 transition-colors">{{ $page->title }}</a>
                    @endforeach
                @endisset
            </div>
        </div>
    </div>
</footer>
