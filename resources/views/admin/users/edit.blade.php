@extends('admin.layouts.app')
@section('title', __('admin.edit') . ' ' . __('admin.users'))
@section('header', __('admin.edit') . ' ' . __('admin.users'))
@section('content')
<form action="{{ route('admin.users.update', $user) }}" method="POST" class="max-w-2xl space-y-6">
    @csrf @method('PUT')
    <div class="bg-white dark:bg-gray-900 rounded-xl p-5 border border-gray-200 dark:border-gray-800 space-y-4">
        <div><label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('admin.name') }} *</label><input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 focus:ring-2 focus:ring-violet-500"></div>
        <div><label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('admin.email') }} *</label><input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 focus:ring-2 focus:ring-violet-500"></div>
        <div><label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('admin.password') }} <span class="text-gray-500 text-xs">({{ __('admin.leave_empty') }})</span></label><input type="password" name="password" id="password" class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 focus:ring-2 focus:ring-violet-500"></div>
        <div><label for="password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('admin.password_confirmation') }}</label><input type="password" name="password_confirmation" id="password_confirmation" class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 focus:ring-2 focus:ring-violet-500"></div>
        <div><label for="role" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('admin.role') }} *</label><select name="role" id="role" required class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900"><option value="user" {{ old('role', $user->role) === 'user' ? 'selected' : '' }}>User</option><option value="admin" {{ old('role', $user->role) === 'admin' ? 'selected' : '' }}>Admin</option></select></div>
    </div>
    <div class="flex gap-2"><button type="submit" class="py-2 px-6 bg-violet-600 hover:bg-violet-700 text-white text-sm font-medium rounded-lg">{{ __('admin.update') }}</button><a href="{{ route('admin.users.index') }}" class="py-2 px-6 bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 text-sm font-medium rounded-lg">{{ __('admin.cancel') }}</a></div>
</form>
@endsection
