<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class OutletMicStarted implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public int $outletId,
        public string $outletName,
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
        return 'outlet.mic.started';
    }

    public function broadcastWith(): array
    {
        return [
            'outlet_id' => $this->outletId,
            'outlet_name' => $this->outletName,
            'room_id' => $this->roomId,
        ];
    }
}
