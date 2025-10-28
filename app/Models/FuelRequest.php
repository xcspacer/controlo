<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FuelRequest extends Model
{
    protected $fillable = [
        'user_id',
        'total_quantity',
        'delivery_date',
        'notes',
        'request_data'
    ];

    protected $casts = [
        'request_data' => 'array',
        'delivery_date' => 'date',
        'total_quantity' => 'integer'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getTotalFormattedAttribute(): string
    {
        return number_format($this->total_quantity, 0, ',', '.') . ' LT';
    }
}