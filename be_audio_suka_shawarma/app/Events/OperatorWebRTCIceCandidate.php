<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class OperatorWebRTCIceCandidate implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public int $outletId,
        public string $roomId,
        public array $candidate,
    ) {}

    public function broadcastOn(): array
    {
        return [
            new Channel('broadcast.' . $this->roomId),
        ];
    }

    public function broadcastAs(): string
    {
        return 'operator.webrtc.ice';
    }

    public function broadcastWith(): array
    {
        return [
            'outlet_id' => $this->outletId,
            'room_id' => $this->roomId,
            'candidate' => $this->candidate,
        ];
    }
}
