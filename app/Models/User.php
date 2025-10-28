<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'is_admin',
        'email_verified_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    
    public function stations(): BelongsToMany
    {
        return $this->belongsToMany(Station::class)->withTimestamps();
    }
    
    public function hasStationWithoutCurrentSurvey(): bool
    {
        if ($this->is_admin) {
            return Station::where('is_active', true)
                ->whereDoesntHave('surveys', function ($query) {
                    $query->where('month', now()->month)
                          ->where('year', now()->year);
                })->exists();
        }
        
        foreach ($this->stations as $station) {
            if ($station->is_active && !$station->hasCurrentSurvey()) {
                return true;
            }
        }
        
        return false;
    }
}