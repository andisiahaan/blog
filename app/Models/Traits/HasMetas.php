<?php

namespace App\Models\Traits;

use App\Models\Meta;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait HasMetas
{
    /**
     * Get all metas for this model.
     */
    public function metas(): MorphMany
    {
        return $this->morphMany(Meta::class, 'metable');
    }

    /**
     * Get a meta value by key.
     */
    public function getMeta(string $key, mixed $default = null): mixed
    {
        $meta = $this->metas()->where('meta_key', $key)->first();

        return $meta ? $meta->meta_value : $default;
    }

    /**
     * Set a meta value.
     */
    public function setMeta(string $key, mixed $value): Meta
    {
        $meta = $this->metas()->updateOrCreate(
            ['meta_key' => $key],
            ['meta_value' => $value]
        );

        return $meta;
    }

    /**
     * Remove a meta by key.
     */
    public function removeMeta(string $key): bool
    {
        return $this->metas()->where('meta_key', $key)->delete() > 0;
    }

    /**
     * Check if meta exists.
     */
    public function hasMeta(string $key): bool
    {
        return $this->metas()->where('meta_key', $key)->exists();
    }

    /**
     * Get all metas as array.
     */
    public function getAllMetas(): array
    {
        return $this->metas()->pluck('meta_value', 'meta_key')->toArray();
    }
}
