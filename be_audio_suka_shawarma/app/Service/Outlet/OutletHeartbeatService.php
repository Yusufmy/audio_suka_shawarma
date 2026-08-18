<?php

namespace App\Service\Outlet;

use App\Events\OutletPresenceUpdated;
use App\Models\Outlet;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class OutletHeartbeatService
{
    /**
     * Dipakai outlet (Flutter) - lewat FcmService, dipanggil setiap
     * kali status foreground/background berubah, DAN secara
     * berkala (heartbeat) supaya last_seen_at tetap segar selama
     * app masih hidup. "offline" dikirim best-effort saat app
     * benar-benar ditutup dengan normal (tidak selalu berhasil
     * kalau di-kill paksa - untuk itu ada pengecekan basi di
     * OutletAuthService::index()).
     */
    public function updatePresence(int $outletId, string $presence): array
    {
        $outlet = Outlet::findOrFail($outletId);

        $status = $presence === 'offline' ? 'offline' : 'online';

        $outlet->update([
            'status' => $status,
            'presence' => $presence,
            'last_seen_at' => now(),
        ]);

        broadcast(
            new OutletPresenceUpdated(
                $outlet->id,
                $status,
                $presence,
                $outlet->last_seen_at?->toISOString(),
            )
        );

        return [
            'id' => $outlet->id,
            'status' => $outlet->status,
            'presence' => $outlet->presence,
            'last_seen_at' => $outlet->last_seen_at,
        ];
    }

    ///ONLINE
    public function heartBeat($outlet): array
    {
        $outlet->update([
            'status' => 'online',
            'last_seen_at' => now(),
        ]);

        return [
            'id' => $outlet->id,
            'code' => $outlet->code,
            'name' => $outlet->name,
            'status' => $outlet->status,
            'last_seen_at' => $outlet->last_seen_at,
        ];
    }

    ///OFFLINE
    public function disconnect($outlet): array
    {
        $outlet->update([
            'status' => 'offline',
        ]);

        return [
            'id' => $outlet->id,
            'code' => $outlet->code,
            'name' => $outlet->name,
            'status' => $outlet->status,
            'last_seen_at' => $outlet->last_seen_at,
        ];
    }
}
