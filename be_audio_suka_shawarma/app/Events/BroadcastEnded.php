<?php

namespace App\Events;

use App\Models\Broadcast;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class BroadcastEnded implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public Broadcast $broadcast
    ) {}

    /**
     * Channel WebSocket.
     */
    public function broadcastOn(): array
    {
        return [
            new Channel('outlets'),
        ];
    }

    /**
     * Nama event yang diterima client.
     */
    public function broadcastAs(): string
    {
        return 'broadcast.ended';
    }

    /**
     * Data yang dikirim ke client.
     */
    public function broadcastWith(): array
    {
        return [
            'broadcast_id' => $this->broadcast->id,
            'type' => $this->broadcast->type,
            'target_mode' => $this->broadcast->target_mode,
            'status' => $this->broadcast->status,
            'rtc_room_id' => $this->broadcast->rtc_room_id,
            'started_at' => $this->broadcast->started_at?->toISOString(),
            'ended_at' => $this->broadcast->ended_at?->toISOString(),
            'duration_seconds' => $this->broadcast->duration_seconds,
        ];
    }
}
