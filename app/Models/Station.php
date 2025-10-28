<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Station extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'group',
        'address',
        'city',
        'is_active',
    ];

    public function fuels(): HasMany
    {
        return $this->hasMany(Fuel::class);
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class)->withTimestamps();
    }

    public function surveys(): HasMany
    {
        return $this->hasMany(Survey::class);
    }

    public function currentSurvey()
    {
        $currentMonth = now()->month;
        $currentYear = now()->year;

        return $this->surveys()
            ->where('month', $currentMonth)
            ->where('year', $currentYear)
            ->first();
    }

    public function hasCurrentSurvey(): bool
    {
        return $this->currentSurvey() !== null;
    }

    public function getLatestSurvey()
    {
        return $this->surveys()
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->first();
    }

    public function fuelLoads(): HasMany
    {
        return $this->hasMany(FuelLoad::class);
    }
}