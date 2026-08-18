<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class WebRTCReceiverReady implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public string $roomId,
        public int $outletId,
    ) {}

    public function broadcastOn(): array
    {
        // SENGAJA di channel 'outlets' (bukan 'broadcast.{roomId}').
        // Channel broadcast.{roomId} baru dibuat/di-subscribe operator
        // SETELAH tahu rtc_room_id (dari response API start broadcast)
        // - butuh round-trip WS "pusher:subscribe" ->
        // "pusher_internal:subscription_succeeded" yang BELUM TENTU
        // selesai duluan dibanding outlet (yang sudah connect sejak
        // awal) sempat terima broadcast.started, connect, dan kirim
        // sinyal ready ini. Kalau operator belum selesai subscribe
        // saat event ini lewat, event-nya HILANG (bukan di-replay) -
        // operator nyangkut di "Waiting for Flutter receiver..."
        // walau outlet-nya sebenarnya sudah siap. outlets channel
        // sudah pasti ke-subscribe jauh sebelum broadcast mana pun
        // dimulai, jadi race ini tidak mungkin terjadi di sana.
        return [
            new Channel('outlets'),
        ];
    }

    public function broadcastAs(): string
    {
        return 'webrtc.receiver.ready';
    }

    public function broadcastWith(): array
    {
        return [
            'room_id' => $this->roomId,
            'outlet_id' => $this->outletId,
        ];
    }
}
