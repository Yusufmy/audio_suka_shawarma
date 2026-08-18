<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class OutletWebRTCOffer implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public int $outletId,
        public string $roomId,
        public array $offer,
    ) {}

    public function broadcastOn(): array
    {
        return [
            new Channel(
                'broadcast.' . $this->roomId
            ),
        ];
    }

    public function broadcastAs(): string
    {
        return 'webrtc.outlet.offer';
    }

    public function broadcastWith(): array
    {
        return [
            'outlet_id' => $this->outletId,
            'room_id' => $this->roomId,
            'offer' => $this->offer,
        ];
    }
}
