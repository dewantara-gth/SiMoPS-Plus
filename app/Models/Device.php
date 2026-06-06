<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
    protected $fillable = [
        'code',
        'name',
        'location',
        'is_active',
        'last_seen_at'
    ];
    
    public function solarReadings()
    {
        return $this->hasMany(SolarReading::class);
    }
}