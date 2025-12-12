@extends('auth.layout')

@section('title', __('auth.login'))

@section('content')
<div>
    <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">{{ __('auth.welcome_back') }}</h2>
    <p class="text-gray-600 dark:text-gray-400 mb-8">{{ __('auth.enter_details') }}</p>

    @if (session('status'))
        <div class="mb-4 p-4 rounded-lg bg-green-50 dark:bg-green-900/30 text-green-700 dark:text-green-400 text-sm">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}" class="space-y-6">
        @csrf

        <!-- Email -->
        <div>
            <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                {{ __('auth.email') }}
            </label>
            <input 
                id="email" 
                type="email" 
                name="email" 
                value="{{ old('email') }}" 
                required 
                autofocus 
                autocomplete="username"
                class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-white placeholder-gray-400 focus:ring-2 focus:ring-violet-500 focus:border-transparent transition-all duration-200 @error('email') border-red-500 @enderror"
                placeholder="name@example.com"
            >
            @error('email')
                <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
            @enderror
        </div>

        <!-- Password -->
        <div>
            <div class="flex items-center justify-between mb-2">
                <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                    {{ __('auth.password') }}
                </label>
                <a href="{{ route('password.request') }}" class="text-sm text-violet-600 dark:text-violet-400 hover:underline">
                    {{ __('auth.forgot_password') }}
                </a>
            </div>
            <input 
                id="password" 
                type="password" 
                name="password" 
                required 
                autocomplete="current-password"
                class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-white placeholder-gray-400 focus:ring-2 focus:ring-violet-500 focus:border-transparent transition-all duration-200 @error('password') border-red-500 @enderror"
                placeholder="••••••••"
            >
            @error('password')
                <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
            @enderror
        </div>

        <!-- Remember Me -->
        <div class="flex items-center">
            <input 
                id="remember" 
                type="checkbox" 
                name="remember" 
                class="w-4 h-4 text-violet-600 bg-gray-100 dark:bg-gray-800 border-gray-300 dark:border-gray-600 rounded focus:ring-violet-500"
            >
            <label for="remember" class="ml-2 text-sm text-gray-600 dark:text-gray-400">
                {{ __('auth.remember_me') }}
            </label>
        </div>

        <!-- Submit Button -->
        <button 
            type="submit"
            class="w-full py-3 px-4 bg-gradient-to-r from-violet-600 to-purple-600 hover:from-violet-700 hover:to-purple-700 text-white font-semibold rounded-lg shadow-lg shadow-violet-500/30 hover:shadow-violet-500/50 transition-all duration-200 transform hover:-translate-y-0.5"
        >
            {{ __('auth.sign_in') }}
        </button>
    </form>

    <!-- Register Link -->
    <p class="mt-8 text-center text-sm text-gray-600 dark:text-gray-400">
        {{ __('auth.no_account') }}
        <a href="{{ route('register') }}" class="text-violet-600 dark:text-violet-400 font-medium hover:underline">
            {{ __('auth.create_account') }}
        </a>
    </p>
</div>
@endsection
