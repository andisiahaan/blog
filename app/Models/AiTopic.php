<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AiTopic extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'keywords',
        'tone',
        'language',
        'min_words',
        'max_words',
        'category_id',
        'is_active',
        'priority',
        'posts_generated',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'min_words' => 'integer',
        'max_words' => 'integer',
        'priority' => 'integer',
        'posts_generated' => 'integer',
    ];

    /**
     * Get the category for this topic.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get the generation logs for this topic.
     */
    public function logs(): HasMany
    {
        return $this->hasMany(AiGenerationLog::class);
    }

    /**
     * Scope to get only active topics.
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to order by priority descending.
     */
    public function scopeByPriority(Builder $query): Builder
    {
        return $query->orderByDesc('priority');
    }

    /**
     * Get keywords as array.
     */
    public function getKeywordsArrayAttribute(): array
    {
        if (empty($this->keywords)) {
            return [];
        }

        return array_map('trim', explode(',', $this->keywords));
    }

    /**
     * Increment posts generated count.
     */
    public function incrementPostsGenerated(): void
    {
        $this->increment('posts_generated');
    }
}
