<?php

namespace App\Http\Controllers;

use App\Events\OperatorWebRTCAnswer;
use App\Events\OperatorWebRTCIceCandidate;
use App\Events\OutletMicStarted;
use App\Events\OutletMicStopped;
use App\Events\OutletThumbsUp;
use App\Events\OutletWebRTCIceCandidate;
use App\Events\OutletWebRTCOffer;
use App\Events\WebRTCAnswer;
use App\Events\WebRTCIceCandidate;
use App\Events\WebRTCOffer;
use App\Events\WebRTCOperatorToOutletIce;
use App\Events\WebRTCReceiverReady;
use Illuminate\Http\Request;

class WebRTCController extends Controller
{
    // ============================================================
    // OPERATOR → OUTLET
    // ============================================================

    public function offer(Request $request)
    {
        $validated = $request->validate([
            'room_id' => ['required', 'string'],
            'offer' => ['required', 'array'],
            'outlet_id' => ['required', 'integer'],
        ]);

        broadcast(
            new WebRTCOffer(
                $validated['room_id'],
                $validated['offer'],
                $validated['outlet_id']
            )
        );

        return response()->json([
            'message' => 'WebRTC offer sent',
        ]);
    }

    public function answer(Request $request)
    {
        $validated = $request->validate([
            'room_id' => ['required', 'string'],
            'answer' => ['required', 'array'],
            'outlet_id' => ['required', 'integer'],
        ]);

        broadcast(
            new WebRTCAnswer(
                $validated['room_id'],
                $validated['answer'],
                $validated['outlet_id']
            )
        );

        return response()->json([
            'message' => 'WebRTC answer sent',
        ]);
    }

    public function ice(Request $request)
    {
        $validated = $request->validate([
            'room_id' => ['required', 'string'],
            'candidate' => ['required', 'array'],
            'outlet_id' => ['required', 'integer'],
        ]);

        broadcast(
            new WebRTCIceCandidate(
                $validated['room_id'],
                $validated['candidate'],
                $validated['outlet_id']
            )
        );

        return response()->json([
            'message' => 'ICE candidate sent',
        ]);
    }

    // ============================================================
    // OPERATOR → OUTLET : ICE (siaran bicara langsung)
    // ============================================================

    public function operatorIceForBroadcast(Request $request)
    {
        $validated = $request->validate([
            'room_id' => ['required', 'string'],
            'candidate' => ['required', 'array'],
            'outlet_id' => ['required', 'integer'],
        ]);

        broadcast(
            new WebRTCOperatorToOutletIce(
                $validated['room_id'],
                $validated['candidate'],
                $validated['outlet_id']
            )
        );

        return response()->json([
            'message' => 'Operator ICE candidate sent',
        ]);
    }

    public function receiverReady(Request $request)
    {
        $validated = $request->validate([
            'room_id' => ['required', 'string'],
            'outlet_id' => ['required', 'integer'],
        ]);

        broadcast(
            new WebRTCReceiverReady(
                $validated['room_id'],
                $validated['outlet_id']
            )
        );

        return response()->json([
            'message' => 'Receiver ready signal sent',
        ]);
    }

    // ============================================================
    // OUTLET → OPERATOR : OPEN MIC
    // ============================================================

    public function outletMicStart(Request $request)
    {
        $validated = $request->validate([
            'outlet_id' => ['required', 'integer'],
            'outlet_name' => ['required', 'string'],
            'room_id' => ['required', 'string'],
        ]);

        broadcast(
            new OutletMicStarted(
                $validated['outlet_id'],
                $validated['outlet_name'],
                $validated['room_id']
            )
        );

        return response()->json([
            'message' => 'Outlet mic started',
        ]);
    }

    public function outletMicStop(Request $request)
    {
        $validated = $request->validate([
            'outlet_id' => ['required', 'integer'],
            'room_id' => ['required', 'string'],
        ]);

        broadcast(
            new OutletMicStopped(
                $validated['outlet_id'],
                $validated['room_id']
            )
        );

        return response()->json([
            'message' => 'Outlet mic stopped',
        ]);
    }

    // ============================================================
    // OUTLET → OPERATOR : THUMBS UP (konfirmasi suara sudah keluar)
    // ============================================================

    public function thumbsUp(Request $request)
    {
        $validated = $request->validate([
            'outlet_id' => ['required', 'integer'],
            'outlet_name' => ['required', 'string'],
            'room_id' => ['nullable', 'string'],
        ]);

        broadcast(
            new OutletThumbsUp(
                $validated['outlet_id'],
                $validated['outlet_name'],
                $validated['room_id'] ?? null
            )
        );

        return response()->json([
            'message' => 'Thumbs up sent',
        ]);
    }

    // ============================================================
    // OUTLET → OPERATOR : WEBRTC SIGNALING
    // ============================================================

    public function outletOffer(Request $request)
    {
        $validated = $request->validate([
            'outlet_id' => ['required', 'integer'],
            'room_id' => ['required', 'string'],
            'offer' => ['required', 'array'],
        ]);

        broadcast(
            new OutletWebRTCOffer(
                $validated['outlet_id'],
                $validated['room_id'],
                $validated['offer']
            )
        );

        return response()->json([
            'message' => 'Outlet WebRTC offer sent',
        ]);
    }

    public function operatorAnswer(Request $request)
    {
        $validated = $request->validate([
            'outlet_id' => ['required', 'integer'],
            'room_id' => ['required', 'string'],
            'answer' => ['required', 'array'],
        ]);

        broadcast(
            new OperatorWebRTCAnswer(
                $validated['outlet_id'],
                $validated['room_id'],
                $validated['answer']
            )
        );

        return response()->json([
            'message' => 'Operator WebRTC answer sent',
        ]);
    }

    public function operatorIce(Request $request)
    {
        $validated = $request->validate([
            'outlet_id' => ['required', 'integer'],
            'room_id' => ['required', 'string'],
            'candidate' => ['required', 'array'],
        ]);

        broadcast(
            new OperatorWebRTCIceCandidate(
                $validated['outlet_id'],
                $validated['room_id'],
                $validated['candidate']
            )
        );

        return response()->json([
            'message' => 'Operator WebRTC ICE sent',
        ]);
    }

    public function outletIce(Request $request)
    {
        $validated = $request->validate([
            'outlet_id' => ['required', 'integer'],
            'room_id' => ['required', 'string'],
            'candidate' => ['required', 'array'],
        ]);

        broadcast(
            new OutletWebRTCIceCandidate(
                $validated['outlet_id'],
                $validated['room_id'],
                $validated['candidate']
            )
        );

        return response()->json([
            'message' => 'Outlet WebRTC ICE sent',
        ]);
    }
}
