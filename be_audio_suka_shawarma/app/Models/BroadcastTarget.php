<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BroadcastTarget extends Model
{
    protected $fillable = [
        'broadcast_id',
        'outlet_id',
        'status',
        'delivered_at',
        'completed_at',
        'error_message',
    ];

    protected function casts(): array
    {
        return [
            'delivered_at' => 'datetime',
            'completed_at' => 'datetime',
        ];
    }

    /**
     * Broadcast yang menjadi target.
     */
    public function broadcast(): BelongsTo
    {
        return $this->belongsTo(Broadcast::class, 'broadcast_id');
    }

    /**
     * Outlet penerima broadcast.
     */
    public function outlet(): BelongsTo
    {
        return $this->belongsTo(Outlet::class, 'outlet_id');
    }
}
