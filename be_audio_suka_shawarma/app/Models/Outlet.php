<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;

class Outlet extends Authenticatable implements JWTSubject
{
    use SoftDeletes;

    protected $fillable = [
        'code',
        'name',
        'status',
        'presence',
        'is_busy',
        'last_seen_at',
        'paired_at',
        'device_info',
        'fcm_token',
    ];

    protected function casts(): array
    {
        return [
            'is_busy' => 'boolean',
            'last_seen_at' => 'datetime',
            'paired_at' => 'datetime',
            'device_info' => 'array',
        ];
    }

    /**
     * JWT identifier.
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * JWT custom claims.
     */
    public function getJWTCustomClaims()
    {
        return [
            'type' => 'outlet',
            'outlet_id' => $this->id,
            'code' => $this->code,
        ];
    }

    /**
     * Broadcast target milik outlet.
     */
    public function broadcastsTargets(): HasMany
    {
        return $this->hasMany(
            BroadcastTarget::class,
            'outlet_id'
        );
    }

    /**
     * Event/log milik outlet.
     */
    public function events(): HasMany
    {
        return $this->hasMany(
            OutletEvent::class,
            'outlet_id'
        );
    }
}
