<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Broadcast extends Model
{
    /**
     * Field yang boleh diisi secara mass assignment.
     */
    protected $fillable = [
        'operator_id',
        'type',
        'audio_file_id',
        'target_mode',
        'status',
        'scheduled_at',
        'started_at',
        'ended_at',
        'duration_seconds',
        'rtc_room_id',
    ];

    /**
     * Casting attribute.
     */
    protected function casts(): array
    {
        return [
            'scheduled_at' => 'datetime',
            'started_at' => 'datetime',
            'ended_at' => 'datetime',
            'duration_seconds' => 'integer',
        ];
    }

    /**
     * Broadcast dibuat oleh satu operator.
     */
    public function operator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'operator_id');
    }

    /**
     * Broadcast dapat menggunakan satu file audio.
     *
     * NULL untuk broadcast Live.
     */
    public function audioFile(): BelongsTo
    {
        return $this->belongsTo(AudioFile::class, 'audio_file_id');
    }

    /**
     * Satu broadcast memiliki banyak target outlet.
     */
    public function targets(): HasMany
    {
        return $this->hasMany(BroadcastTarget::class, 'broadcast_id');
    }
}
