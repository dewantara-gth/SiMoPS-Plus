<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RelayEvent extends Model
{
    protected $fillable = [
        'device_id', 'mode', 'relay_state', 'changed_by_user_id', 'reason', 'changed_at'
    ];
    
    protected $casts = [
        'changed_at' => 'datetime'
    ];
}