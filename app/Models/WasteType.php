<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WasteType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
    ];

    public function wasteReports()
    {
        return $this->hasMany(WasteReport::class);
    }
}
