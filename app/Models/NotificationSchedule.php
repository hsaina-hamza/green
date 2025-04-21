<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;

class NotificationSchedule extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'user_id',
        'notification_type',
        'notification_data',
        'frequency',
        'scheduled_at',
        'last_sent_at',
        'active',
        'settings',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'notification_data' => 'array',
        'settings' => 'array',
        'scheduled_at' => 'datetime',
        'last_sent_at' => 'datetime',
        'active' => 'boolean',
    ];

    /**
     * The available frequency options.
     *
     * @var array<string>
     */
    public static array $frequencies = [
        'once',
        'daily',
        'weekly',
        'monthly',
        'custom',
    ];

    /**
     * Get the user that owns the notification schedule.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Schedule a new notification.
     *
     * @param \App\Models\User $user
     * @param string $type
     * @param array $data
     * @param string $frequency
     * @param \Carbon\Carbon|null $scheduledAt
     * @return static
     */
    public static function schedule(
        User $user,
        string $type,
        array $data,
        string $frequency = 'once',
        ?Carbon $scheduledAt = null
    ): self {
        return static::create([
            'user_id' => $user->id,
            'notification_type' => $type,
            'notification_data' => $data,
            'frequency' => $frequency,
            'scheduled_at' => $scheduledAt ?? now(),
            'active' => true,
        ]);
    }

    /**
     * Mark the schedule as sent and update the next scheduled time.
     *
     * @return bool
     */
    public function markAsSent(): bool
    {
        $this->last_sent_at = now();

        if ($this->frequency === 'once') {
            $this->active = false;
        } else {
            $this->scheduled_at = $this->calculateNextSchedule();
        }

        return $this->save();
    }

    /**
     * Calculate the next scheduled time based on frequency.
     *
     * @return \Carbon\Carbon
     */
    protected function calculateNextSchedule(): Carbon
    {
        $now = now();

        return match($this->frequency) {
            'daily' => $now->addDay()->setTime(
                $this->scheduled_at->hour,
                $this->scheduled_at->minute
            ),
            'weekly' => $now->addWeek()->setTime(
                $this->scheduled_at->hour,
                $this->scheduled_at->minute
            )->startOfWeek()->addDays(
                $this->scheduled_at->dayOfWeek
            ),
            'monthly' => $now->addMonth()->setTime(
                $this->scheduled_at->hour,
                $this->scheduled_at->minute
            )->setDay(min(
                $this->scheduled_at->day,
                $now->addMonth()->daysInMonth
            )),
            'custom' => $this->calculateCustomSchedule(),
            default => $now,
        };
    }

    /**
     * Calculate custom schedule based on settings.
     *
     * @return \Carbon\Carbon
     */
    protected function calculateCustomSchedule(): Carbon
    {
        $settings = $this->settings ?? [];
        $interval = $settings['interval'] ?? 'days';
        $value = $settings['value'] ?? 1;

        return now()->add($interval, $value)->setTime(
            $this->scheduled_at->hour,
            $this->scheduled_at->minute
        );
    }

    /**
     * Scope a query to only include active schedules.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('active', true);
    }

    /**
     * Scope a query to only include schedules due for sending.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeDue(Builder $query): Builder
    {
        return $query->active()
            ->where('scheduled_at', '<=', now());
    }

    /**
     * Scope a query to only include schedules for a specific frequency.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $frequency
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWithFrequency(Builder $query, string $frequency): Builder
    {
        return $query->where('frequency', $frequency);
    }

    /**
     * Scope a query to only include schedules for a specific notification type.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $type
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOfType(Builder $query, string $type): Builder
    {
        return $query->where('notification_type', $type);
    }

    /**
     * Update the schedule settings.
     *
     * @param array $settings
     * @return bool
     */
    public function updateSettings(array $settings): bool
    {
        $this->settings = array_merge($this->settings ?? [], $settings);
        return $this->save();
    }

    /**
     * Pause the schedule.
     *
     * @return bool
     */
    public function pause(): bool
    {
        return $this->update(['active' => false]);
    }

    /**
     * Resume the schedule.
     *
     * @return bool
     */
    public function resume(): bool
    {
        if ($this->frequency === 'once' && $this->last_sent_at) {
            return false;
        }

        return $this->update([
            'active' => true,
            'scheduled_at' => $this->calculateNextSchedule(),
        ]);
    }

    /**
     * Get the next scheduled notification time.
     *
     * @return \Carbon\Carbon|null
     */
    public function getNextScheduledTime(): ?Carbon
    {
        if (!$this->active) {
            return null;
        }

        return $this->scheduled_at;
    }

    /**
     * Clean up completed one-time schedules.
     *
     * @param int $days
     * @return int
     */
    public static function cleanCompletedSchedules(int $days = 30): int
    {
        return static::where('frequency', 'once')
            ->where('active', false)
            ->where('last_sent_at', '<=', now()->subDays($days))
            ->delete();
    }
}
