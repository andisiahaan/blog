<?php

use App\Models\Setting;
use Illuminate\Support\Facades\Cache;

if (!function_exists('setting')) {
    /**
     * Get or set a setting value with caching support.
     *
     * @param string|array|null $key
     * @param mixed $default
     * @return mixed
     */
    function setting(string|array|null $key = null, mixed $default = null): mixed
    {
        // The cache key used for all settings
        $cacheKey = 'settings.all';

        // Default TTL
        $ttl = config('cache.ttl', 3600);

        // If no specific key is provided: return all settings
        if ($key === null) {
            // Cache all settings
            return Cache::remember($cacheKey, $ttl, function () {
                // Fetch all settings as key-value pairs
                return Setting::all()->pluck('value', 'key')->toArray();
            });
        }

        // If array provided → set multiple values
        if (is_array($key)) {
            foreach ($key as $k => $v) {
                Setting::updateOrCreate(
                    ['key' => $k],
                    ['value' => $v]
                );
            }

            // Clear cache because settings updated
            Cache::forget($cacheKey);

            return true;
        }

        // Get single value
        $all = Cache::remember($cacheKey, $ttl, function () {
            // Fetch all settings as key-value pairs
            return Setting::all()->pluck('value', 'key')->toArray();
        });

        return $all[$key] ?? $default;
    }
}
