<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FuelLoad extends Model
{
    protected $fillable = [
        'station_id',
        'fuel_id',
        'survey_id',
        'user_id',
        'day',
        'current_sounding',
        'load_amount',
        'notes'
    ];

    public function station(): BelongsTo
    {
        return $this->belongsTo(Station::class);
    }

    public function fuel(): BelongsTo
    {
        return $this->belongsTo(Fuel::class);
    }

    public function survey(): BelongsTo
    {
        return $this->belongsTo(Survey::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}