<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="{ darkMode: localStorage.getItem('darkMode') === 'true', sidebarOpen: true }" :class="{ 'dark': darkMode }">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') - {{ __('admin.dashboard') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/trix@2.0.8/dist/trix.css">
    <script src="https://unpkg.com/trix@2.0.8/dist/trix.umd.min.js"></script>
    @if(setting('site_favicon'))
    <link rel="icon" href="{{ Storage::disk('public')->url(setting('site_favicon')) }}" type="image/x-icon">
    @endif
</head>
<body class="font-sans antialiased bg-gray-50 dark:bg-black text-gray-900 dark:text-white">
    <div class="min-h-screen flex">
        <!-- Sidebar -->
        @include('admin.layouts.partials.sidebar')

        <!-- Main Content -->
        <div class="flex-1 flex flex-col min-w-0" :class="{ 'lg:ml-64': sidebarOpen }">
            <!-- Header -->
            @include('admin.layouts.partials.header')

            <!-- Page Content -->
            <main class="flex-1 p-4 md:p-6">
                <!-- Flash Messages -->
                @if (session('success'))
                    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
                         class="mb-4 p-4 rounded-lg bg-green-50 dark:bg-green-900/30 text-green-700 dark:text-green-400 flex items-center justify-between">
                        <span>{{ session('success') }}</span>
                        <button @click="show = false" class="text-green-500 hover:text-green-700">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>
                @endif

                @if (session('error'))
                    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
                         class="mb-4 p-4 rounded-lg bg-red-50 dark:bg-red-900/30 text-red-700 dark:text-red-400 flex items-center justify-between">
                        <span>{{ session('error') }}</span>
                        <button @click="show = false" class="text-red-500 hover:text-red-700">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    @stack('scripts')
</body>
</html>
