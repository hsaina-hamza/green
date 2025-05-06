<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'address',
        'latitude',
        'longitude',
        'site_id',
    ];

    /**
     * Get the coordinates as a string.
     */
    public function getCoordinatesAttribute(): string
    {
        return "{$this->latitude}, {$this->longitude}";
    }

    /**
     * Scope a query to only include locations with coordinates.
     */
    public function scopeHasCoordinates($query)
    {
        return $query->whereNotNull('latitude')->whereNotNull('longitude');
    }

    public function wasteReports()
    {
        return $this->hasMany(WasteReport::class);
    }

    public function garbageTruckSchedules()
    {
        return $this->hasMany(GarbageTruckSchedule::class);
    }

    public function busTimes()
    {
        return $this->hasMany(BusTime::class);
    }

    public function site()
    {
        return $this->belongsTo(Site::class);
    }
}
