<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class AudioFile extends Model
{
    use SoftDeletes;
    /**
     * Field yang boleh diisi secara mass assignment.
     */
    protected $fillable = [
        'operator_id',
        'original_name',
        'file_path',
        'duration_seconds',
        'size_bytes',
    ];

    /**
     * Casting attribute.
     */
    protected function casts(): array
    {
        return [
            'duration_seconds' => 'integer',
            'size_bytes' => 'integer',
        ];
    }

    /**
     * Audio dimiliki oleh satu operator.
     */
    public function operator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'operator_id');
    }

    /**
     * Satu audio dapat digunakan pada banyak broadcast.
     */
    public function broadcasts(): HasMany
    {
        return $this->hasMany(Broadcast::class, 'audio_file_id');
    }
}
