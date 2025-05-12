<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WasteReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'waste_type_id',
        'location_id',
        'quantity',
        'unit',
        'description',
        'reported_by',
        'status',
        'image_path',
    ];

    /**
     * Get the waste type associated with the report.
     */
    public function wasteType()
    {
        return $this->belongsTo(WasteType::class);
    }

    /**
     * Get the location associated with the report.
     */
    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    /**
     * Get the site associated with the report.
     */
    public function site()
    {
        return $this->belongsTo(Site::class);
    }

    /**
     * Get the user who reported the waste.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'reported_by');
    }

    /**
     * Get the reporter of the waste report.
     */
    public function reporter()
    {
        return $this->belongsTo(User::class, 'reported_by');
    }

    /**
     * Get the worker assigned to the report.
     */
    public function assignedWorker()
    {
        return $this->belongsTo(User::class, 'worker_id');
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
     * Check if the report is resolved.
     */
    public function isResolved(): bool
    {
        return $this->status === 'resolved';
    }
}
