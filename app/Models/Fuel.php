<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Fuel extends Model
{
    protected $fillable = [
        'station_id',
        'name',
        'counter',
        'capacity',
        'is_active',
    ];

    public function station(): BelongsTo
    {
        return $this->belongsTo(Station::class)->where('is_active', true);
    }
}