<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class OutletMicStopped implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public int $outletId,
        public string $roomId,
    ) {}

    public function broadcastOn(): array
    {
        return [
            new Channel('operator'),
        ];
    }

    public function broadcastAs(): string
    {
        return 'outlet.mic.stopped';
    }

    public function broadcastWith(): array
    {
        return [
            'outlet_id' => $this->outletId,
            'room_id' => $this->roomId,
        ];
    }
}
