<?php

namespace App\Events\audio;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AudioWebRTCOffer implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public int $outlet_id;

    public string $room_id;

    public array $offer;

    public function __construct(
        int $outlet_id,
        string $room_id,
        array $offer
    ) {
        $this->outlet_id = $outlet_id;
        $this->room_id = $room_id;
        $this->offer = $offer;
    }

    public function broadcastOn(): array
    {
        return [
            new Channel(
                'audio.' . $this->room_id
            ),
        ];
    }

    public function broadcastAs(): string
    {
        return 'audio.webrtc.offer';
    }

    public function broadcastWith(): array
    {
        return [
            'outlet_id' => $this->outlet_id,

            'room_id' => $this->room_id,

            'offer' => $this->offer,
        ];
    }
}
