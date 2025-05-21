<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;

class GarbageSchedule extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'site_id',
        'truck_number',
        'scheduled_time',
        'frequency',
        'notes',
        'status',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'scheduled_time' => 'datetime',
    ];

    /**
     * The relationships that should always be loaded.
     *
     * @var array<string>
     */
    protected $with = ['site'];

    /**
     * Valid frequency options.
     *
     * @var array<string>
     */
    public const FREQUENCIES = [
        'once',
        'daily',
        'weekly',
        'biweekly',
        'monthly',
    ];

    /**
     * Get the site associated with the schedule.
     */
    public function site(): BelongsTo
    {
        return $this->belongsTo(Site::class);
    }

    /**
     * Scope a query to only include upcoming schedules.
     */
    public function scopeUpcoming(Builder $query): Builder
    {
        return $query->where('scheduled_time', '>=', now())
                    ->orderBy('scheduled_time', 'asc');
    }

    /**
     * Scope a query to only include active schedules.
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope a query to only include past schedules.
     */
    public function scopePast(Builder $query): Builder
    {
        return $query->where('scheduled_time', '<', now())
                    ->orderBy('scheduled_time', 'desc');
    }

    /**
     * Scope a query to only include today's schedules.
     */
    public function scopeToday(Builder $query): Builder
    {
        return $query->whereDate('scheduled_time', Carbon::today())
                    ->orderBy('scheduled_time', 'asc');
    }

    /**
     * Scope a query to only include this week's schedules.
     */
    public function scopeThisWeek(Builder $query): Builder
    {
        return $query->whereBetween('scheduled_time', [
            Carbon::now()->startOfWeek(),
            Carbon::now()->endOfWeek(),
        ])->orderBy('scheduled_time', 'asc');
    }

    /**
     * Check if the schedule is upcoming.
     */
    public function isUpcoming(): bool
    {
        return $this->scheduled_time->isFuture();
    }

    /**
     * Check if the schedule is past.
     */
    public function isPast(): bool
    {
        return $this->scheduled_time->isPast();
    }

    /**
     * Get the formatted scheduled time.
     */
    public function getFormattedScheduleAttribute(): string
    {
        return $this->scheduled_time->format('Y-m-d H:i');
    }

    /**
     * Get the human readable scheduled time.
     */
    public function getHumanScheduleAttribute(): string
    {
        return $this->scheduled_time->diffForHumans();
    }

    /**
     * Get the formatted frequency.
     */
    public function getFormattedFrequencyAttribute(): string
    {
        return ucfirst($this->frequency);
    }

    /**
     * Get the next occurrence of this schedule.
     */
    public function getNextOccurrenceAttribute(): ?Carbon
    {
        if ($this->frequency === 'once') {
            return null;
        }

        $lastSchedule = $this->scheduled_time;
        $now = Carbon::now();

        while ($lastSchedule->isPast()) {
            switch ($this->frequency) {
                case 'daily':
                    $lastSchedule = $lastSchedule->addDay();
                    break;
                case 'weekly':
                    $lastSchedule = $lastSchedule->addWeek();
                    break;
                case 'biweekly':
                    $lastSchedule = $lastSchedule->addWeeks(2);
                    break;
                case 'monthly':
                    $lastSchedule = $lastSchedule->addMonth();
                    break;
            }
        }

        return $lastSchedule;
    }
}
