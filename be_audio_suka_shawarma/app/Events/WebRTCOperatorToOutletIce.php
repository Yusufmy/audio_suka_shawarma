<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

/**
 * ICE candidate operator -> outlet, untuk siaran bicara langsung.
 *
 * Dipisah dari WebRTCIceCandidate (outlet -> operator) supaya
 * tidak perlu lagi deteksi "ICE milik sendiri" lewat ufrag -
 * masing-masing arah punya event sendiri, persis pola yang
 * dipakai fitur audio file (AudioWebRTCOperatorIceCandidate).
 */
class WebRTCOperatorToOutletIce implements ShouldBroadcastNow
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
        return 'webrtc.operator.ice';
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
