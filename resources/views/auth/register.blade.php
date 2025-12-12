@extends('auth.layout')

@section('title', __('auth.register'))

@section('content')
<div>
    <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">{{ __('auth.create_new_account') }}</h2>
    <p class="text-gray-600 dark:text-gray-400 mb-8">{{ __('auth.enter_details') }}</p>

    <form method="POST" action="{{ route('register') }}" class="space-y-5">
        @csrf

        <!-- Name -->
        <div>
            <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                {{ __('auth.name') }}
            </label>
            <input 
                id="name" 
                type="text" 
                name="name" 
                value="{{ old('name') }}" 
                required 
                autofocus 
                autocomplete="name"
                class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-white placeholder-gray-400 focus:ring-2 focus:ring-violet-500 focus:border-transparent transition-all duration-200 @error('name') border-red-500 @enderror"
                placeholder="{{ __('auth.name') }}"
            >
            @error('name')
                <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
            @enderror
        </div>

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
            <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                {{ __('auth.password') }}
            </label>
            <input 
                id="password" 
                type="password" 
                name="password" 
                required 
                autocomplete="new-password"
                class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-white placeholder-gray-400 focus:ring-2 focus:ring-violet-500 focus:border-transparent transition-all duration-200 @error('password') border-red-500 @enderror"
                placeholder="••••••••"
            >
            @error('password')
                <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
            @enderror
        </div>

        <!-- Confirm Password -->
        <div>
            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                {{ __('auth.password_confirmation') }}
            </label>
            <input 
                id="password_confirmation" 
                type="password" 
                name="password_confirmation" 
                required 
                autocomplete="new-password"
                class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-white placeholder-gray-400 focus:ring-2 focus:ring-violet-500 focus:border-transparent transition-all duration-200"
                placeholder="••••••••"
            >
        </div>

        <!-- Terms and Privacy -->
        <div class="flex items-start">
            <input 
                id="terms" 
                type="checkbox" 
                name="terms" 
                value="1"
                class="w-4 h-4 mt-1 text-violet-600 bg-gray-100 dark:bg-gray-800 border-gray-300 dark:border-gray-600 rounded focus:ring-violet-500 @error('terms') border-red-500 @enderror"
                required
            >
            <label for="terms" class="ml-2 text-sm text-gray-600 dark:text-gray-400">
                {!! __('auth.terms_agree', [
                    'terms' => '<a href="' . route('pages.show', 'terms-of-service') . '" target="_blank" class="text-violet-600 dark:text-violet-400 hover:underline">' . __('auth.terms_of_service') . '</a>',
                    'privacy' => '<a href="' . route('pages.show', 'privacy-policy') . '" target="_blank" class="text-violet-600 dark:text-violet-400 hover:underline">' . __('auth.privacy_policy') . '</a>'
                ]) !!}
            </label>
        </div>
        @error('terms')
            <p class="text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
        @enderror

        <!-- Submit Button -->
        <button 
            type="submit"
            class="w-full py-3 px-4 bg-gradient-to-r from-violet-600 to-purple-600 hover:from-violet-700 hover:to-purple-700 text-white font-semibold rounded-lg shadow-lg shadow-violet-500/30 hover:shadow-violet-500/50 transition-all duration-200 transform hover:-translate-y-0.5"
        >
            {{ __('auth.create_account') }}
        </button>
    </form>

    <!-- Login Link -->
    <p class="mt-8 text-center text-sm text-gray-600 dark:text-gray-400">
        {{ __('auth.already_registered') }}
        <a href="{{ route('login') }}" class="text-violet-600 dark:text-violet-400 font-medium hover:underline">
            {{ __('auth.sign_in') }}
        </a>
    </p>
</div>
@endsection
