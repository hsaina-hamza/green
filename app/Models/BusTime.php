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

    protected $casts = [
        'departure_time' => 'datetime',
        'arrival_time' => 'datetime',
    ];

    public function location()
    {
        return $this->belongsTo(Location::class);
    }
}
