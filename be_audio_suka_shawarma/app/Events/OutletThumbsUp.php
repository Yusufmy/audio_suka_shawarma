<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

/**
 * Outlet menekan tombol "sudah dapat siaran, suaranya sudah keluar"
 * (jempol) di layar Receiving Broadcast - dipakai operator untuk
 * tahu outlet mana yang SUDAH BENAR-BENAR dengar suaranya, bukan
 * cuma "WebRTC-nya connected" (itu event yang beda, receiver.ready).
 */
class OutletThumbsUp implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public int $outletId,
        public string $outletName,
        public ?string $roomId,
    ) {}

    public function broadcastOn(): array
    {
        return [
            new Channel('outlets'),
        ];
    }

    public function broadcastAs(): string
    {
        return 'outlet.thumbs.up';
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
