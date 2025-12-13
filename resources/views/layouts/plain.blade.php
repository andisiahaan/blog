<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="{ darkMode: localStorage.getItem('darkMode') === 'true' }" :class="{ 'dark': darkMode }">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>{{ $title }} - {{ setting('site_name', config('app.name')) }}</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    @if(setting('site_favicon'))
    <link rel="icon" href="{{ Storage::disk('public')->url(setting('site_favicon')) }}" type="image/x-icon">
    @endif
    
    {{ $head ?? '' }}
</head>
<body class="font-sans antialiased bg-white dark:bg-black text-gray-900 dark:text-white min-h-screen">
    <div class="min-h-screen flex flex-col md:flex-row">
        <!-- Left Side - Decorative -->
        <div class="hidden md:flex md:w-1/2 bg-gradient-to-br from-violet-600 via-purple-600 to-indigo-700 relative overflow-hidden">
            <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg width=\"60\" height=\"60\" viewBox=\"0 0 60 60\" xmlns=\"http://www.w3.org/2000/svg\"%3E%3Cg fill=\"none\" fill-rule=\"evenodd\"%3E%3Cg fill=\"%23ffffff\" fill-opacity=\"0.05\"%3E%3Cpath d=\"M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\"/%3E%3C/g%3E%3C/g%3E%3C/svg%3E')] opacity-30"></div>
            <div class="relative z-10 flex flex-col items-center justify-center w-full p-12 text-white">
                <div class="max-w-md text-center">
                    <h1 class="text-4xl font-bold mb-6">{{ setting('site_name', config('app.name')) }}</h1>
                    <p class="text-lg text-white/80 mb-8">{{ __('auth.welcome_message', ['name' => setting('site_name', config('app.name'))]) }}</p>
                    <div class="flex items-center justify-center space-x-1 text-sm text-white/60">
                        <span class="w-2 h-2 rounded-full bg-green-400 animate-pulse"></span>
                        <span>{{ __('auth.secure_connection') }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Side - Form -->
        <div class="flex-1 flex items-center justify-center p-4 md:p-12">
            <div class="w-full max-w-md">
                <!-- Mobile Logo -->
                <div class="md:hidden text-center mb-8">
                    <h1 class="text-2xl font-bold text-violet-600 dark:text-violet-400">{{ setting('site_name', config('app.name')) }}</h1>
                </div>

                <!-- Dark Mode Toggle -->
                <div class="flex justify-end mb-6">
                    <button 
                        @click="darkMode = !darkMode; localStorage.setItem('darkMode', darkMode)"
                        class="p-2 rounded-full hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors"
                        :title="darkMode ? '{{ __('common.light_mode') }}' : '{{ __('common.dark_mode') }}'"
                    >
                        <svg x-show="!darkMode" class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path>
                        </svg>
                        <svg x-show="darkMode" class="w-5 h-5 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path>
                        </svg>
                    </button>
                </div>

                {{ $slot }}
            </div>
        </div>
    </div>
</body>
</html>
