<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BusSchedule extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'neighborhood',
        'route_name',
        'departure_time',
        'arrival_time',
        'frequency',
        'notes',
        'is_active',
        'created_by',
        'updated_by',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'departure_time' => 'datetime:H:i',
        'arrival_time' => 'datetime:H:i',
        'is_active' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * Valid frequency options.
     *
     * @var array<string>
     */
    public const FREQUENCIES = [
        'daily' => 'Daily',
        'weekdays' => 'Weekdays Only',
        'weekends' => 'Weekends Only',
    ];

    /**
     * Get the user who created the schedule.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user who last updated the schedule.
     */
    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Scope a query to only include active schedules.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to only include schedules for a specific neighborhood.
     */
    public function scopeInNeighborhood($query, $neighborhood)
    {
        return $query->where('neighborhood', $neighborhood);
    }

    /**
     * Scope a query to only include schedules with a specific frequency.
     */
    public function scopeWithFrequency($query, $frequency)
    {
        return $query->where('frequency', $frequency);
    }

    /**
     * Get the formatted departure time.
     */
    public function getFormattedDepartureTimeAttribute(): string
    {
        return $this->departure_time->format('H:i');
    }

    /**
     * Get the formatted arrival time.
     */
    public function getFormattedArrivalTimeAttribute(): string
    {
        return $this->arrival_time->format('H:i');
    }

    /**
     * Get the human-readable frequency.
     */
    public function getFrequencyLabelAttribute(): string
    {
        return self::FREQUENCIES[$this->frequency] ?? $this->frequency;
    }
}
