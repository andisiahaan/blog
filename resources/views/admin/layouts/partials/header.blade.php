<!-- Header -->
<header class="h-16 bg-white dark:bg-gray-900 border-b border-gray-200 dark:border-gray-800 flex items-center justify-between px-4">
    <!-- Left: Hamburger + Breadcrumb -->
    <div class="flex items-center space-x-4">
        <button @click="sidebarOpen = !sidebarOpen" class="p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
        </button>
        <h1 class="text-lg font-semibold text-gray-900 dark:text-white">@yield('header', __('admin.dashboard'))</h1>
    </div>

    <!-- Right: Actions -->
    <div class="flex items-center space-x-2">
        <!-- Dark Mode Toggle -->
        <button @click="darkMode = !darkMode; localStorage.setItem('darkMode', darkMode.toString())"
                class="p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors"
                :title="darkMode ? '{{ __('common.light_mode') }}' : '{{ __('common.dark_mode') }}'">
            <template x-if="!darkMode">
                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path>
                </svg>
            </template>
            <template x-if="darkMode">
                <svg class="w-5 h-5 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path>
                </svg>
            </template>
        </button>

        <!-- User Dropdown -->
        <div x-data="{ open: false }" class="relative">
            <button @click="open = !open" class="flex items-center space-x-2 p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
                <div class="w-8 h-8 rounded-full bg-gradient-to-br from-violet-600 to-purple-600 flex items-center justify-center text-white text-sm font-medium">
                    {{ substr(auth()->user()->name, 0, 1) }}
                </div>
                <span class="hidden md:block text-sm font-medium text-gray-700 dark:text-gray-300">{{ auth()->user()->name }}</span>
                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
            </button>

            <div x-show="open" @click.away="open = false" x-transition
                 class="absolute right-0 mt-2 w-48 bg-white dark:bg-gray-900 rounded-lg shadow-lg border border-gray-200 dark:border-gray-800 py-1 z-50">
                <div class="px-4 py-2 border-b border-gray-200 dark:border-gray-800">
                    <p class="text-sm font-medium text-gray-900 dark:text-white">{{ auth()->user()->name }}</p>
                    <p class="text-xs text-gray-500">{{ auth()->user()->email }}</p>
                </div>
                <a href="{{ route('admin.profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800">
                    {{ __('admin.profile') }}
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full text-left px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800">
                        {{ __('admin.logout') }}
                    </button>
                </form>
            </div>

        </div>
    </div>
</header>
