<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WasteReport extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'title',
        'description',
        'type',
        'urgency_level',
        'status',
        'site_id',
        'user_id',
        'worker_id',
        'estimated_size',
        'location_details',
<<<<<<< HEAD
=======
        'latitude',
        'longitude',
>>>>>>> 231977c8c8cc7dfc8f6b499ce1a4fff2b8175808
        'image_url',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'estimated_size' => 'integer',
<<<<<<< HEAD
=======
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
>>>>>>> 231977c8c8cc7dfc8f6b499ce1a4fff2b8175808
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * Get the site that owns the waste report.
     */
    public function site()
    {
        return $this->belongsTo(Site::class);
    }

    /**
     * Get the user that created the waste report.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the worker assigned to the waste report.
     */
    public function worker()
    {
        return $this->belongsTo(User::class, 'worker_id');
    }

    /**
     * Get the comments for the waste report.
     */
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * Scope a query to only include active reports.
     */
    public function scopeActive($query)
    {
        return $query->whereIn('status', ['pending', 'in_progress']);
    }

    /**
     * Scope a query to only include pending reports.
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope a query to only include in-progress reports.
     */
    public function scopeInProgress($query)
    {
        return $query->where('status', 'in_progress');
    }

    /**
     * Scope a query to only include completed reports.
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    /**
     * Check if the report is active.
     */
    public function isActive(): bool
    {
        return in_array($this->status, ['pending', 'in_progress']);
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
     * Check if the report is assigned to a worker.
     */
    public function isAssigned(): bool
    {
        return $this->worker_id !== null;
    }
}
