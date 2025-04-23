<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BusTime extends Model
{
    use HasFactory;

    protected $fillable = [
        'location_id',
        'route',
        'departure_time',
        'arrival_time',
        'frequency',
        'notes'
    ];

    /**
     * Get the location that owns the bus time.
     */
    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    /**
     * Get the formatted departure time.
     */
    public function getDepartureTimeAttribute($value)
    {
        return substr($value, 0, 5);
    }

    /**
     * Get the formatted arrival time.
     */
    public function getArrivalTimeAttribute($value)
    {
        return substr($value, 0, 5);
    }
}
