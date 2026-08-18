<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AudioBroadcastStarted implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public string $roomId;
    public array $audio;
    public array $outletIds;

    public function __construct(
        string $roomId,
        array $audio,
        array $outletIds = []
    ) {
        $this->roomId = $roomId;
        $this->audio = $audio;
        $this->outletIds = $outletIds;
    }

    public function broadcastOn(): array
    {
        return [
            new Channel('outlets'),
        ];
    }

    public function broadcastAs(): string
    {
        return 'audio.broadcast.started';
    }

    public function broadcastWith(): array
    {
        return [
            'room_id' => $this->roomId,
            'audio' => $this->audio,
            'outlet_ids' => $this->outletIds,
        ];
    }
}
