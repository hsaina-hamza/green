<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GarbageSchedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'site_id',
        'truck_number',
        'scheduled_time',
    ];

    protected $casts = [
        'scheduled_time' => 'datetime',
    ];

    protected $with = ['site'];

    public function site()
    {
        return $this->belongsTo(Site::class);
    }

    public function scopeUpcoming($query)
    {
        return $query->where('scheduled_time', '>=', now())
                    ->orderBy('scheduled_time', 'asc');
    }

    public function scopePast($query)
    {
        return $query->where('scheduled_time', '<', now())
                    ->orderBy('scheduled_time', 'desc');
    }
}
