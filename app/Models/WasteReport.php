<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;

class WasteReport extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'user_id',
        'site_id',
        'waste_type',
        'severity',
        'description',
        'image',
        'status',
        'assigned_worker_id',
    ];

    /**
     * The relationships that should always be loaded.
     *
     * @var array<string>
     */
    protected $with = ['user', 'site'];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'assigned_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    /**
     * Valid waste types.
     *
     * @var array<string>
     */
    public const WASTE_TYPES = [
        'plastic',
        'paper',
        'glass',
        'organic',
        'other',
    ];

    /**
     * Valid severity levels.
     *
     * @var array<string>
     */
    public const SEVERITY_LEVELS = [
        'low',
        'medium',
        'high',
    ];

    /**
     * Valid statuses.
     *
     * @var array<string>
     */
    public const STATUSES = [
        'pending',
        'in_progress',
        'completed',
    ];

    /**
     * Get the user that created the report.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the site associated with the report.
     */
    public function site(): BelongsTo
    {
        return $this->belongsTo(Site::class);
    }

    /**
     * Get the worker assigned to the report.
     */
    public function assignedWorker(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_worker_id');
    }

    /**
     * Get the comments for the report.
     */
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * Scope a query to only include pending reports.
     */
    public function scopePending(Builder $query): Builder
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope a query to only include in-progress reports.
     */
    public function scopeInProgress(Builder $query): Builder
    {
        return $query->where('status', 'in_progress');
    }

    /**
     * Scope a query to only include completed reports.
     */
    public function scopeCompleted(Builder $query): Builder
    {
        return $query->where('status', 'completed');
    }

    /**
     * Scope a query to only include reports with high severity.
     */
    public function scopeHighSeverity(Builder $query): Builder
    {
        return $query->where('severity', 'high');
    }

    /**
     * Get the image URL attribute.
     */
    public function getImageUrlAttribute(): ?string
    {
        return $this->image ? asset('storage/' . $this->image) : null;
    }

    /**
     * Check if the report is pending.
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    /**
     * Check if the report is in progress.
     */
    public function isInProgress(): bool
    {
        return $this->status === 'in_progress';
    }

    /**
     * Check if the report is completed.
     */
    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    /**
     * Check if the report has high severity.
     */
    public function isHighSeverity(): bool
    {
        return $this->severity === 'high';
    }

    /**
     * Check if the report is assigned to a worker.
     */
    public function isAssigned(): bool
    {
        return !is_null($this->assigned_worker_id);
    }

    /**
     * Get the time taken to complete the report.
     */
    public function getCompletionTime(): ?string
    {
        if (!$this->isCompleted() || !$this->assigned_at) {
            return null;
        }

        $start = $this->assigned_at;
        $end = $this->completed_at;

        return $end->diffForHumans($start, true);
    }
}
