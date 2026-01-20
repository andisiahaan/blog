<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AiGenerationLog extends Model
{
    use HasFactory;

    public const STATUS_PENDING = 'pending';
    public const STATUS_PROCESSING = 'processing';
    public const STATUS_SUCCESS = 'success';
    public const STATUS_FAILED = 'failed';

    protected $fillable = [
        'ai_topic_id',
        'post_id',
        'provider',
        'model',
        'status',
        'prompt',
        'generated_title',
        'tokens_used',
        'cost',
        'error_message',
        'generation_time_ms',
    ];

    protected $casts = [
        'tokens_used' => 'integer',
        'cost' => 'decimal:6',
        'generation_time_ms' => 'integer',
    ];

    /**
     * Get the topic for this log.
     */
    public function topic(): BelongsTo
    {
        return $this->belongsTo(AiTopic::class, 'ai_topic_id');
    }

    /**
     * Get the generated post.
     */
    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }

    /**
     * Scope to get only successful generations.
     */
    public function scopeSuccessful(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_SUCCESS);
    }

    /**
     * Scope to get only failed generations.
     */
    public function scopeFailed(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_FAILED);
    }

    /**
     * Scope to get pending generations.
     */
    public function scopePending(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    /**
     * Mark as processing.
     */
    public function markAsProcessing(): void
    {
        $this->update(['status' => self::STATUS_PROCESSING]);
    }

    /**
     * Mark as successful.
     */
    public function markAsSuccess(Post $post, int $tokensUsed, float $cost, int $timeMs): void
    {
        $this->update([
            'status' => self::STATUS_SUCCESS,
            'post_id' => $post->id,
            'generated_title' => $post->title,
            'tokens_used' => $tokensUsed,
            'cost' => $cost,
            'generation_time_ms' => $timeMs,
        ]);
    }

    /**
     * Mark as failed.
     */
    public function markAsFailed(string $errorMessage): void
    {
        $this->update([
            'status' => self::STATUS_FAILED,
            'error_message' => $errorMessage,
        ]);
    }

    /**
     * Check if can be retried.
     */
    public function canRetry(): bool
    {
        return $this->status === self::STATUS_FAILED;
    }
}
