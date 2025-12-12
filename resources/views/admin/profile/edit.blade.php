@extends('admin.layouts.app')
@section('title', __('admin.profile'))
@section('header', __('admin.profile'))

@section('content')
<div class="max-w-2xl space-y-6">
    {{-- Profile Information --}}
    <div class="bg-white dark:bg-gray-900 rounded-xl p-5 border border-gray-200 dark:border-gray-800">
        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">{{ __('admin.profile_information') }}</h3>
        <p class="text-sm text-gray-600 dark:text-gray-400 mb-6">{{ __('admin.profile_information_desc') }}</p>
        
        <form action="{{ route('admin.profile.update') }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')

            {{-- Name --}}
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('admin.name') }}</label>
                <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required
                       class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 focus:ring-2 focus:ring-violet-500 @error('name') border-red-500 @enderror">
                @error('name')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            {{-- Email --}}
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('admin.email') }}</label>
                <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required
                       class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 focus:ring-2 focus:ring-violet-500 @error('email') border-red-500 @enderror">
                @error('email')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex justify-end">
                <button type="submit" class="px-6 py-2 bg-violet-600 hover:bg-violet-700 text-white font-medium rounded-lg transition-colors">
                    {{ __('admin.save') }}
                </button>
            </div>
        </form>
    </div>

    {{-- Update Password --}}
    <div class="bg-white dark:bg-gray-900 rounded-xl p-5 border border-gray-200 dark:border-gray-800">
        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">{{ __('admin.update_password') }}</h3>
        <p class="text-sm text-gray-600 dark:text-gray-400 mb-6">{{ __('admin.update_password_desc') }}</p>
        
        <form action="{{ route('admin.profile.password') }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')

            {{-- Current Password --}}
            <div>
                <label for="current_password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('admin.current_password') }}</label>
                <input type="password" name="current_password" id="current_password" required
                       class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 focus:ring-2 focus:ring-violet-500 @error('current_password') border-red-500 @enderror">
                @error('current_password')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            {{-- New Password --}}
            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('admin.new_password') }}</label>
                <input type="password" name="password" id="password" required
                       class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 focus:ring-2 focus:ring-violet-500 @error('password') border-red-500 @enderror">
                @error('password')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            {{-- Confirm Password --}}
            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('admin.password_confirmation') }}</label>
                <input type="password" name="password_confirmation" id="password_confirmation" required
                       class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 focus:ring-2 focus:ring-violet-500">
            </div>

            <div class="flex justify-end">
                <button type="submit" class="px-6 py-2 bg-violet-600 hover:bg-violet-700 text-white font-medium rounded-lg transition-colors">
                    {{ __('admin.update_password') }}
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
