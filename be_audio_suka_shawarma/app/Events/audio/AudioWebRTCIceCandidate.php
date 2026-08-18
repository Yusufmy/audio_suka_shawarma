<?php

namespace App\Events\audio;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AudioWebRTCIceCandidate implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * ============================================================
     * DATA
     * ============================================================
     */

    public int $outlet_id;
    public string $roomId;
    public array $candidate;

    /**
     * ============================================================
     * CONSTRUCTOR
     * ============================================================
     */

    public function __construct(
        int $outlet_id,
        string $roomId,
        array $candidate
    ) {
        $this->outlet_id = $outlet_id;
        $this->roomId = $roomId;
        $this->candidate = $candidate;
    }

    /**
     * ============================================================
     * BROADCAST CHANNEL
     * ============================================================
     */

    public function broadcastOn(): array
    {
        return [
            new Channel('audio.' . $this->roomId),
        ];
    }

    /**
     * ============================================================
     * EVENT NAME
     * ============================================================
     */

    public function broadcastAs(): string
    {
        return 'audio.webrtc.ice';
    }

    /**
     * ============================================================
     * DATA YANG DIKIRIM
     * ============================================================
     */

    public function broadcastWith(): array
    {
        return [
            'outlet_id' => $this->outlet_id,
            'room_id' => $this->roomId,
            'candidate' => $this->candidate,
        ];
    }
}
