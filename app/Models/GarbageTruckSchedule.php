<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GarbageTruckSchedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'location_id',
        'truck_number',
        'scheduled_time',
    ];

    public function location()
    {
        return $this->belongsTo(Location::class);
    }
}
