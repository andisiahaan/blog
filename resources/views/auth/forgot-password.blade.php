<x-layouts.plain :title="__('auth.forgot_password')">
<div>
    <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">{{ __('auth.forgot_password') }}</h2>
    <p class="text-gray-600 dark:text-gray-400 mb-8">{{ __('auth.forgot_password_text') }}</p>

    @if (session('status'))
        <div class="mb-6 p-4 rounded-lg bg-green-50 dark:bg-green-900/30 text-green-700 dark:text-green-400 text-sm">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}" class="space-y-6">
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
                class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-white placeholder-gray-400 focus:ring-2 focus:ring-violet-500 focus:border-transparent transition-all duration-200 @error('email') border-red-500 @enderror"
                placeholder="name@example.com"
            >
            @error('email')
                <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
            @enderror
        </div>

        <!-- Submit Button -->
        <button 
            type="submit"
            class="w-full py-3 px-4 bg-gradient-to-r from-violet-600 to-purple-600 hover:from-violet-700 hover:to-purple-700 text-white font-semibold rounded-lg shadow-lg shadow-violet-500/30 hover:shadow-violet-500/50 transition-all duration-200 transform hover:-translate-y-0.5"
        >
            {{ __('auth.send_reset_link') }}
        </button>
    </form>

    <!-- Back to Login -->
    <p class="mt-8 text-center text-sm text-gray-600 dark:text-gray-400">
        <a href="{{ route('login') }}" class="text-violet-600 dark:text-violet-400 font-medium hover:underline">
            &larr; {{ __('auth.back_to_login') }}
        </a>
    </p>
</div>
</x-layouts.plain>
