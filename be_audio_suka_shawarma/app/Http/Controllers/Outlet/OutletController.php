<?php

namespace App\Http\Controllers\Outlet;

use App\Http\Controllers\Controller;
use App\Http\Requests\Outlet\OutletHeartbeatRequest;
use App\Service\Outlet\OutletHeartbeatService;
use Illuminate\Http\Request;

class OutletController extends Controller
{
    public function __construct(
        protected OutletHeartbeatService $outletHearbeatService
    ) {}

    public function heartBeat(OutletHeartbeatRequest $request)
    {
        $outlet = $request->attributes->get('outlet');

        $result = $this->outletHearbeatService->heartBeat($outlet);

        return response()->json([
            'message' => 'Heartbeat berhasil',
            'data' => [
                'outlet' => $result,
            ],
        ]);
    }

    public function disconnect(Request $request)
    {
        $outlet = $request->attributes->get('outlet');

        $result = $this->outletHearbeatService
            ->disconnect($outlet);

        return response()->json([
            'message' => 'Outlet berhasil disconnect',
            'data' => [
                'outlet' => $result,
            ],
        ]);
    }

    // ============================================================
    // PRESENCE (foreground/background/offline) - dipanggil outlet
    // (Flutter) tiap kali app pindah state, dan berkala selama
    // masih hidup. Tidak pakai jwt.outlet, ikut pola outlet_id di
    // body seperti endpoint outlet lain di codebase ini.
    // ============================================================

    public function updatePresence(Request $request)
    {
        $validated = $request->validate([
            'outlet_id' => ['required', 'integer', 'exists:outlets,id'],
            'presence' => ['required', 'in:foreground,background,offline'],
        ]);

        $result = $this->outletHearbeatService->updatePresence(
            $validated['outlet_id'],
            $validated['presence']
        );

        return response()->json([
            'message' => 'Presence berhasil diperbarui',
            'data' => $result,
        ]);
    }
}
