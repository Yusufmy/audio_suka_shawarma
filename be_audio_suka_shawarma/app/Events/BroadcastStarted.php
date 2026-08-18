<?php

namespace App\Events;

use App\Models\Broadcast;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class BroadcastStarted implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public Broadcast $broadcast
    ) {}

    public function broadcastOn(): array
    {
        return [
            new Channel('outlets'),
        ];
    }

    public function broadcastAs(): string
    {
        return 'broadcast.started';
    }

    public function broadcastWith(): array
    {
        $outletIds = [];

        if ($this->broadcast->target_mode === 'specific') {
            $outletIds = $this->broadcast
                ->targets()
                ->pluck('outlet_id')
                ->map(fn ($id) => (string) $id)
                ->values()
                ->toArray();
        }

        return [
            'broadcast_id' => $this->broadcast->id,

            'type' => $this->broadcast->type,

            'target_mode' =>
                $this->broadcast->target_mode,

            'outlet_ids' =>
                $outletIds,

            'status' =>
                $this->broadcast->status,

            'rtc_room_id' =>
                $this->broadcast->rtc_room_id,

            'started_at' =>
                $this->broadcast
                    ->started_at
                    ?->toISOString(),
        ];
    }
}
