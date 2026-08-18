<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

/**
 * Status "kehidupan" app outlet berubah - foreground (sedang
 * dipakai), background (masih hidup tapi tidak di layar), atau
 * offline (tertutup/mati, atau heartbeat-nya sudah basi).
 */
class OutletPresenceUpdated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public int $outletId,
        public string $status,
        public string $presence,
        public ?string $lastSeenAt,
    ) {}

    public function broadcastOn(): array
    {
        return [
            new Channel('outlets'),
        ];
    }

    public function broadcastAs(): string
    {
        return 'outlet.presence.updated';
    }

    public function broadcastWith(): array
    {
        return [
            'outlet_id' => $this->outletId,
            'status' => $this->status,
            'presence' => $this->presence,
            'last_seen_at' => $this->lastSeenAt,
        ];
    }
}
