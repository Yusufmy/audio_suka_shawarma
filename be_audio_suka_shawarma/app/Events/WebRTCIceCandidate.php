<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class WebRTCIceCandidate implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public string $roomId,
        public array $candidate,
        public int $outletId,
    ) {}

    public function broadcastOn(): array
    {
        return [
            new Channel('broadcast.' . $this->roomId),
        ];
    }

    public function broadcastAs(): string
    {
        return 'webrtc.ice';
    }

    public function broadcastWith(): array
    {
        return [
            'room_id' => $this->roomId,
            'candidate' => $this->candidate,
            'outlet_id' => $this->outletId,
        ];
    }
}
