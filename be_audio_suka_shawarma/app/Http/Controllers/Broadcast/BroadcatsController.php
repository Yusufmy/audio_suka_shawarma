<?php

namespace App\Http\Controllers\Broadcast;

use App\Http\Controllers\Controller;
use App\Http\Requests\Broadcast\BroadcastRequest;
use App\Service\Broadcast\BroadcastService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BroadcatsController extends Controller
{
    public function __construct(
        protected BroadcastService $broadcastService
    ) {}

    /**
     * Start live broadcast.
     */
    public function start(BroadcastRequest $request): JsonResponse
    {
        $result = $this->broadcastService->start(
            $request->validated()
        );

        return response()->json([
            'message' => 'Broadcast berhasil dimulai',
            'data' => $result,
        ], 200);
    }

    /**
     * End live broadcast.
     */
    public function end(int $broadcastId): JsonResponse
    {
        $result = $this->broadcastService->end(
            $broadcastId
        );

        return response()->json([
            'message' => 'Broadcast berhasil dihentikan',
            'data' => $result,
        ]);
    }

    /**
     * Cek apakah ada siaran langsung yang sedang berlangsung untuk
     * outlet ini - dipakai outlet Flutter saat app baru dibuka
     * (mis. lewat notifikasi FCM) supaya bisa langsung gabung ke
     * broadcast yang SUDAH berjalan, bukan cuma menunggu event
     * WebSocket baru yang tidak akan pernah datang lagi (broadcast-
     * nya sudah lebih dulu mulai sebelum outlet ini subscribe).
     */
    public function active(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'outlet_id' => ['required', 'integer'],
        ]);

        $data = $this->broadcastService->activeFor(
            $validated['outlet_id']
        );

        return response()->json([
            'active' => $data !== null,
            'data' => $data,
        ]);
    }
}
