<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'latitude',
        'longitude',
    ];

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
}
