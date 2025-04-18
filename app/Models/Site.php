<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;

class Site extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'name',
        'latitude',
        'longitude',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
    ];

    /**
     * Get the waste reports for the site.
     */
    public function wasteReports(): HasMany
    {
        return $this->hasMany(WasteReport::class);
    }

    /**
     * Get the garbage schedules for the site.
     */
    public function garbageSchedules(): HasMany
    {
        return $this->hasMany(GarbageSchedule::class);
    }

    /**
     * Get the location attribute.
     *
     * @return array<string, float>
     */
    public function getLocationAttribute(): array
    {
        return [
            'lat' => (float) $this->latitude,
            'lng' => (float) $this->longitude,
        ];
    }

    /**
     * Get the active waste reports for the site.
     */
    public function activeWasteReports(): HasMany
    {
        return $this->wasteReports()
            ->whereIn('status', ['pending', 'in_progress'])
            ->orderBy('created_at', 'desc');
    }

    /**
     * Get the upcoming garbage schedules for the site.
     */
    public function upcomingSchedules(): HasMany
    {
        return $this->garbageSchedules()
            ->where('scheduled_time', '>=', now())
            ->orderBy('scheduled_time', 'asc');
    }

    /**
     * Get the high severity waste reports for the site.
     */
    public function highSeverityReports(): HasMany
    {
        return $this->wasteReports()
            ->where('severity', 'high')
            ->orderBy('created_at', 'desc');
    }

    /**
     * Scope a query to only include sites with active reports.
     */
    public function scopeWithActiveReports(Builder $query): Builder
    {
        return $query->whereHas('wasteReports', function ($query) {
            $query->whereIn('status', ['pending', 'in_progress']);
        });
    }

    /**
     * Scope a query to only include sites with upcoming schedules.
     */
    public function scopeWithUpcomingSchedules(Builder $query): Builder
    {
        return $query->whereHas('garbageSchedules', function ($query) {
            $query->where('scheduled_time', '>=', now());
        });
    }

    /**
     * Get the formatted coordinates.
     */
    public function getFormattedCoordinatesAttribute(): string
    {
        return "{$this->latitude}, {$this->longitude}";
    }

    /**
     * Get the Google Maps URL for the site.
     */
    public function getGoogleMapsUrlAttribute(): string
    {
        return "https://www.google.com/maps/search/?api=1&query={$this->latitude},{$this->longitude}";
    }

    /**
     * Check if the site has any active reports.
     */
    public function hasActiveReports(): bool
    {
        return $this->activeWasteReports()->exists();
    }

    /**
     * Check if the site has any upcoming schedules.
     */
    public function hasUpcomingSchedules(): bool
    {
        return $this->upcomingSchedules()->exists();
    }

    /**
     * Get the site statistics.
     *
     * @return array<string, int>
     */
    public function getStatistics(): array
    {
        $reports = $this->wasteReports();
        
        return [
            'total_reports' => $reports->count(),
            'pending_reports' => $reports->where('status', 'pending')->count(),
            'in_progress_reports' => $reports->where('status', 'in_progress')->count(),
            'completed_reports' => $reports->where('status', 'completed')->count(),
            'high_severity_reports' => $reports->where('severity', 'high')->count(),
            'upcoming_schedules' => $this->upcomingSchedules()->count(),
        ];
    }
}
