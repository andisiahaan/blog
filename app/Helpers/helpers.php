<?php

use App\Models\Setting;

if (!function_exists('setting')) {
    /**
     * Get or set a setting value.
     *
     * @param string|array|null $key
     * @param mixed $default
     * @return mixed
     */
    function setting(string|array|null $key = null, mixed $default = null): mixed
    {
        // If no key provided, return all settings
        if ($key === null) {
            return Setting::all();
        }

        // If array provided, set multiple values
        if (is_array($key)) {
            foreach ($key as $k => $v) {
                Setting::set($k, $v);
            }
            return true;
        }

        // Get single value
        return Setting::get($key, $default);
    }
}
