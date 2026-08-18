<?php

namespace App\Events\audio;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AudioWebRTCAnswer implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * ============================================================
     * DATA
     * ============================================================
     */

    public int $outlet_id;

    public string $room_id;

    public array $answer;

    /**
     * ============================================================
     * CONSTRUCTOR
     * ============================================================
     */

    public function __construct(
        int $outlet_id,
        string $room_id,
        array $answer
    ) {
        $this->outlet_id = $outlet_id;
        $this->room_id = $room_id;
        $this->answer = $answer;
    }

    /**
     * ============================================================
     * CHANNEL
     * ============================================================
     */

    public function broadcastOn(): array
    {
        return [
            new Channel(
                'audio.' . $this->room_id
            ),
        ];
    }

    /**
     * ============================================================
     * EVENT NAME
     * ============================================================
     */

    public function broadcastAs(): string
    {
        return 'audio.webrtc.answer';
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

            'room_id' => $this->room_id,

            'answer' => $this->answer,
        ];
    }
}
