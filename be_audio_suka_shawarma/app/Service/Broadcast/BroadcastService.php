<?php

namespace App\Service\Broadcast;

use App\Events\BroadcastEnded;
use App\Events\BroadcastStarted;
use App\Models\Broadcast;
use App\Models\BroadcastTarget;
use App\Models\Outlet;
use App\Service\Push\FcmPushService;
use Illuminate\Support\Facades\DB;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class BroadcastService
{
    public function __construct(
        protected FcmPushService $fcmPushService
    ) {}

    /**
     * Cari broadcast LIVE yang sedang aktif dan menargetkan outlet
     * ini (target_mode 'all', atau 'specific' dengan outlet ini
     * termasuk). Bentuk return-nya SAMA PERSIS dengan payload
     * event BroadcastStarted (broadcastWith()), supaya sisi Flutter
     * bisa memakai handler yang sama persis untuk keduanya.
     */
    public function activeFor(int $outletId): ?array
    {
        $broadcast = Broadcast::query()
            ->where('type', 'live')
            ->where('status', 'live')
            ->where(function ($query) use ($outletId) {
                $query->where('target_mode', 'all')
                    ->orWhereHas(
                        'targets',
                        fn ($q) => $q->where('outlet_id', $outletId)
                    );
            })
            ->latest('started_at')
            ->first();

        if (!$broadcast) {
            return null;
        }

        $outletIds = [];

        if ($broadcast->target_mode === 'specific') {
            $outletIds = $broadcast->targets()
                ->pluck('outlet_id')
                ->map(fn ($id) => (string) $id)
                ->values()
                ->toArray();
        }

        return [
            'broadcast_id' => $broadcast->id,
            'type' => $broadcast->type,
            'target_mode' => $broadcast->target_mode,
            'outlet_ids' => $outletIds,
            'status' => $broadcast->status,
            'rtc_room_id' => $broadcast->rtc_room_id,
            'started_at' => $broadcast->started_at?->toISOString(),
        ];
    }

    /**
     * Start Live Brodcast
     */
    public function start(array $data): array
    {
        return DB::transaction(function () use ($data) {

            ///Ambil operator JWT dari Operator
            $operator = JWTAuth::parseToken()->authenticate();

            if (!$operator) {
                throw new NotFoundHttpException(
                    'Operator tidak ditemukan',
                );
            }

            //Cek apakah operator masih memiliki broadcast aktif
            $activeBroadcast = Broadcast::where(
                'operator_id',
                $operator->id,
            )
                ->where('type', 'live')
                ->where('status', 'live')
                ->first();

            if ($activeBroadcast) {
                throw new ConflictHttpException(
                    'Operator masih memiliki broadcast yang sedang berlangsung.'
                );
            }

            /*
             * Generate room ID untuk WebRTC.
             *
             * Contoh:
             * live-1-20260810123456-a8f92
             */
            $rtcRoomId = 'live-'
                . $operator->id
                . '-'
                . now()->format('YmdHis')
                . '-'
                . bin2hex(random_bytes(5));

            // Buat broadcast
            $broadcast = Broadcast::create([
                'operator_id' => $operator->id,
                'type' => 'live',
                'audio_file_id' => null,
                'target_mode' => $data['target_mode'],
                'status' => 'live',
                'started_at' => now(),
                'rtc_room_id' => $rtcRoomId,
            ]);

            /*
             * Tentukan target outlet.
             */
            if ($data['target_mode'] === 'all') {

                $outlets = Outlet::query()
                    ->where('status', 'online')
                    ->get();
            } else {

                $outlets = Outlet::query()
                    ->whereIn('id', $data['outlet_ids'])
                    ->where('status', 'online')
                    ->get();
            }

            /*
             * Simpan outlet sebagai target broadcast.
             */
            foreach ($outlets as $outlet) {
                BroadcastTarget::create([
                    'broadcast_id' => $broadcast->id,
                    'outlet_id' => $outlet->id,
                ]);
            }

            /*
             * Broadcast event ke WebSocket.
             */
            broadcast(
                new BroadcastStarted($broadcast)
            );

            // Push "ada siaran langsung" ke outlet yang sedang
            // background/terminated - dibungkus supaya kegagalan
            // Firebase tidak pernah menggagalkan siaran langsung.
            try {
                $this->fcmPushService->sendLiveBroadcastStarted(
                    $broadcast->rtc_room_id,
                    $outlets->pluck('id')->all()
                );
            } catch (Throwable $e) {
                // Sudah di-log di dalam FcmPushService.
            }

            return [
                'id' => $broadcast->id,
                'type' => $broadcast->type,
                'target_mode' => $broadcast->target_mode,
                'status' => $broadcast->status,
                'started_at' => $broadcast->started_at,
                'rtc_room_id' => $broadcast->rtc_room_id,
                'total_outlets' => $outlets->count(),
            ];
        });
    }

    /**
     * End live broadcast.
     */
    public function end(int $broadcastId): array
    {
        return DB::transaction(function () use ($broadcastId) {

            // Ambil operator dari JWT
            $operator = JWTAuth::parseToken()->authenticate();

            if (!$operator) {
                throw new NotFoundHttpException(
                    'Operator tidak ditemukan.'
                );
            }

            // Cari broadcast milik operator
            $broadcast = Broadcast::where('id', $broadcastId)
                ->where('operator_id', $operator->id)
                ->first();

            if (!$broadcast) {
                throw new NotFoundHttpException(
                    'Broadcast tidak ditemukan.'
                );
            }

            // Pastikan broadcast masih live
            if ($broadcast->status !== 'live') {
                throw new ConflictHttpException(
                    'Broadcast sudah tidak aktif.'
                );
            }

            $endedAt = now();

            // Hitung durasi
            $durationSeconds = $broadcast->started_at
                ? $broadcast->started_at->diffInSeconds($endedAt)
                : 0;

            // Update broadcast
            $broadcast->update([
                'status' => 'completed',
                'ended_at' => $endedAt,
                'duration_seconds' => $durationSeconds,
            ]);

            broadcast(
                new BroadcastEnded($broadcast->fresh())
            );

            try {
                $outletIds = BroadcastTarget::where(
                    'broadcast_id',
                    $broadcast->id
                )->pluck('outlet_id')->all();

                $this->fcmPushService->sendLiveBroadcastEnded(
                    $broadcast->rtc_room_id,
                    $outletIds
                );
            } catch (Throwable $e) {
                // Sudah di-log di dalam FcmPushService.
            }

            return [
                'id' => $broadcast->id,
                'type' => $broadcast->type,
                'target_mode' => $broadcast->target_mode,
                'status' => $broadcast->status,
                'started_at' => $broadcast->started_at,
                'ended_at' => $broadcast->ended_at,
                'duration_seconds' => $broadcast->duration_seconds,
                'rtc_room_id' => $broadcast->rtc_room_id,
            ];
        });
    }
}
