<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    protected $fillable = [
        'group',
        'key',
        'value',
        'type',
    ];

    /**
     * Get cache TTL from config.
     */
    protected static function getCacheTtl(): int
    {
        return config('cache.ttl', 3600);
    }

    /**
     * Get a setting value by key.
     */
    public static function get(string $key, mixed $default = null): mixed
    {
        $cacheKey = 'setting.' . $key;

        return Cache::remember($cacheKey, static::getCacheTtl(), function () use ($key, $default) {
            $setting = static::where('key', $key)->first();
            
            if (!$setting) {
                return $default;
            }

            return static::castValue($setting->value, $setting->type);
        });
    }

    /**
     * Set a setting value.
     */
    public static function set(string $key, mixed $value, string $group = 'general', string $type = 'text'): void
    {
        static::updateOrCreate(
            ['key' => $key],
            [
                'value' => is_array($value) ? json_encode($value) : $value,
                'group' => $group,
                'type' => $type,
            ]
        );

        Cache::forget('setting.' . $key);
        Cache::forget('settings.all');
    }

    /**
     * Get all settings.
     */
    public static function all($columns = ['*'])
    {
        return Cache::remember('settings.all', static::getCacheTtl(), function () {
            return parent::all();
        });
    }

    /**
     * Get settings by group.
     */
    public static function getByGroup(string $group): array
    {
        $settings = static::where('group', $group)->get();
        $result = [];

        foreach ($settings as $setting) {
            $result[$setting->key] = static::castValue($setting->value, $setting->type);
        }

        return $result;
    }

    /**
     * Cast value based on type.
     */
    protected static function castValue(mixed $value, string $type): mixed
    {
        return match ($type) {
            'boolean' => filter_var($value, FILTER_VALIDATE_BOOLEAN),
            'json' => json_decode($value, true),
            'integer' => (int) $value,
            default => $value,
        };
    }

    /**
     * Clear all settings cache.
     */
    public static function clearCache(): void
    {
        $settings = parent::all();
        foreach ($settings as $setting) {
            Cache::forget('setting.' . $setting->key);
        }
        Cache::forget('settings.all');
    }
}
