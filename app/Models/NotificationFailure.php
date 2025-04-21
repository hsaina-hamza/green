<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;

class NotificationFailure extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'notification_id',
        'channel',
        'error_message',
        'error_context',
        'failed_at',
        'resolved_at',
        'resolution_notes',
        'attempts',
        'next_retry_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'error_context' => 'array',
        'failed_at' => 'datetime',
        'resolved_at' => 'datetime',
        'next_retry_at' => 'datetime',
        'attempts' => 'integer',
    ];

    /**
     * The maximum number of retry attempts.
     *
     * @var int
     */
    public const MAX_ATTEMPTS = 3;

    /**
     * Record a new notification failure.
     *
     * @param string|null $notificationId
     * @param string $channel
     * @param string $errorMessage
     * @param array $context
     * @return static
     */
    public static function record(
        ?string $notificationId,
        string $channel,
        string $errorMessage,
        array $context = []
    ): self {
        return static::create([
            'notification_id' => $notificationId,
            'channel' => $channel,
            'error_message' => $errorMessage,
            'error_context' => $context,
            'failed_at' => now(),
            'attempts' => 1,
            'next_retry_at' => static::calculateNextRetryTime(1),
        ]);
    }

    /**
     * Calculate the next retry time based on attempt number.
     *
     * @param int $attempt
     * @return \Carbon\Carbon
     */
    public static function calculateNextRetryTime(int $attempt): \Carbon\Carbon
    {
        // Exponential backoff: 1min, 5min, 15min
        $delays = [1, 5, 15];
        $delay = $delays[min($attempt - 1, count($delays) - 1)];

        return now()->addMinutes($delay);
    }

    /**
     * Mark the failure as resolved.
     *
     * @param string|null $notes
     * @return bool
     */
    public function markAsResolved(?string $notes = null): bool
    {
        return $this->update([
            'resolved_at' => now(),
            'resolution_notes' => $notes,
        ]);
    }

    /**
     * Record a retry attempt.
     *
     * @param string $errorMessage
     * @param array $context
     * @return bool
     */
    public function recordRetry(string $errorMessage, array $context = []): bool
    {
        return $this->update([
            'attempts' => $this->attempts + 1,
            'error_message' => $errorMessage,
            'error_context' => array_merge($this->error_context ?? [], $context),
            'next_retry_at' => $this->shouldRetry() 
                ? static::calculateNextRetryTime($this->attempts + 1)
                : null,
        ]);
    }

    /**
     * Determine if the notification should be retried.
     *
     * @return bool
     */
    public function shouldRetry(): bool
    {
        return !$this->resolved_at && 
               $this->attempts < static::MAX_ATTEMPTS;
    }

    /**
     * Scope a query to only include unresolved failures.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeUnresolved(Builder $query): Builder
    {
        return $query->whereNull('resolved_at');
    }

    /**
     * Scope a query to only include failures ready for retry.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeReadyForRetry(Builder $query): Builder
    {
        return $query->unresolved()
            ->where('attempts', '<', static::MAX_ATTEMPTS)
            ->where('next_retry_at', '<=', now());
    }

    /**
     * Scope a query to only include failures for a specific channel.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $channel
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeForChannel(Builder $query, string $channel): Builder
    {
        return $query->where('channel', $channel);
    }

    /**
     * Get the user that owns the notification failure.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the human-readable status of the failure.
     *
     * @return string
     */
    public function getStatusAttribute(): string
    {
        if ($this->resolved_at) {
            return 'Resolved';
        }

        if ($this->attempts >= static::MAX_ATTEMPTS) {
            return 'Failed';
        }

        if ($this->next_retry_at && $this->next_retry_at->isFuture()) {
            return 'Waiting for retry';
        }

        return 'Ready for retry';
    }

    /**
     * Get the retry delay in minutes.
     *
     * @return int|null
     */
    public function getRetryDelayAttribute(): ?int
    {
        if (!$this->next_retry_at || !$this->next_retry_at->isFuture()) {
            return null;
        }

        return $this->next_retry_at->diffInMinutes(now());
    }

    /**
     * Clean up old resolved failures.
     *
     * @param int $days
     * @return int
     */
    public static function cleanOldFailures(int $days = 30): int
    {
        return static::where('resolved_at', '<=', now()->subDays($days))
            ->delete();
    }

    /**
     * Get failures that need attention (max attempts reached and unresolved).
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getNeedsAttention()
    {
        return static::unresolved()
            ->where('attempts', '>=', static::MAX_ATTEMPTS)
            ->get();
    }
}
