<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OutletEvent extends Model
{

    /**
     * Karena tabel outlet_events hanya memiliki
     * created_at dan tidak memiliki updated_at.
     */
    public const UPDATED_AT = null;

    /**
     * Field yang boleh diisi secara mass assignment.
     */
    protected $fillable = [
        'outlet_id',
        'type',
        'meta',
    ];

    /**
     * Casting attribute.
     */
    protected function casts(): array
    {
        return [
            'meta' => 'array',
        ];
    }

    /**
     * Event dimiliki oleh satu outlet.
     */
    public function outlet(): BelongsTo
    {
        return $this->belongsTo(Outlet::class, 'outlet_id');
    }
}
