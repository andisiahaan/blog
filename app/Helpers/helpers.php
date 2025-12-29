<?php

use App\Models\Setting;
use Illuminate\Support\Facades\Cache;

if (!function_exists('settings')) {
    /**
     * Get all settings as key-value array.
     *
     * @return array
     */
    function settings(): array
    {
        $cacheKey = 'settings.helper.all';
        $ttl = config('cache.ttl', 3600);

        return Cache::remember($cacheKey, $ttl, function () {
            return Setting::query()->get()->pluck('value', 'key')->toArray();
        });
    }
}

if (!function_exists('setting')) {
    /**
     * Get a single setting value by key.
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    function setting(string $key, mixed $default = null): mixed
    {
        return settings()[$key] ?? $default;
    }
}
