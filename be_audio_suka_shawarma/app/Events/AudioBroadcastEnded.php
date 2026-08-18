<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AudioBroadcastEnded implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public string $roomId;

    public function __construct(
        string $roomId
    ) {
        $this->roomId = $roomId;
    }

    public function broadcastOn(): array
    {
        return [
            new Channel('outlets'),
        ];
    }

    public function broadcastAs(): string
    {
        return 'audio.broadcast.ended';
    }

    public function broadcastWith(): array
    {
        return [
            'room_id' => $this->roomId,
        ];
    }
}
