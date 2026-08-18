<?php

namespace App\Http\Controllers;

use App\Events\audio\AudioWebRTCAnswer;
use App\Events\audio\AudioWebRTCIceCandidate;
use App\Events\audio\AudioWebRTCOffer;
use App\Events\audio\AudioWebRTCOperatorIceCandidate;
// use App\Events\audio\AudioBroadcastStarted;
use App\Events\AudioBroadcastEnded;
use App\Events\AudioBroadcastStarted;
use App\Service\Push\FcmPushService;
use Illuminate\Http\Request;

class AudioWebRTCController extends Controller
{
    public function __construct(
        protected FcmPushService $fcmPushService
    ) {}

    // ============================================================
    // OPERATOR → ALL OUTLETS : AUDIO BROADCAST
    // ============================================================

    public function audioBroadcast(Request $request)
    {
        $validated = $request->validate([
            'room_id' => ['required', 'string'],
            'audio_id' => ['required', 'integer'],
            'audio' => ['required', 'array'],
            'audio.name' => ['required', 'string'],
            'audio.url' => ['required', 'string'],
            'outlet_ids' => ['nullable', 'array'],
            'outlet_ids.*' => ['integer'],
        ]);

        broadcast(
            new AudioBroadcastStarted(
                $validated['room_id'],
                [
                    'id' => $validated['audio_id'],
                    'name' => $validated['audio']['name'],
                    'url' => $validated['audio']['url'],
                ],
                $validated['outlet_ids'] ?? []
            )
        );

        // Push FCM untuk outlet yang sedang background/terminated -
        // dibungkus supaya kegagalan Firebase (mis. kredensial
        // belum ada) tidak pernah menggagalkan broadcast utama.
        try {
            $this->fcmPushService->sendAudioBroadcastStarted(
                $validated['room_id'],
                $validated['audio']['url'],
                $validated['audio']['name'],
                $validated['outlet_ids'] ?? []
            );
        } catch (\Throwable $e) {
            // Sudah di-log di dalam FcmPushService.
        }

        return response()->json([
            'message' => 'Audio broadcast sent',
        ]);
    }

    // ============================================================
    // OPERATOR → ALL OUTLETS : AUDIO BROADCAST ENDED
    // ============================================================

    public function audioBroadcastEnd(Request $request)
    {
        $validated = $request->validate([
            'room_id' => ['required', 'string'],
            'outlet_ids' => ['nullable', 'array'],
            'outlet_ids.*' => ['integer'],
            'natural' => ['nullable', 'boolean'],
        ]);

        broadcast(
            new AudioBroadcastEnded(
                $validated['room_id']
            )
        );

        // "natural" = ini otomatis terkirim karena SEMUA outlet
        // WebRTC sudah tuntas memutar sendiri-sendiri, BUKAN karena
        // operator menekan stop manual. Outlet yang sedang
        // background/terminated (jalur FCM) tidak pernah tercatat
        // sebagai outlet WebRTC sama sekali - jadi kalau ini
        // "natural", JANGAN kirim push "ended" ke FCM, supaya
        // outlet itu (yang bisa saja baru mulai/masih di tengah
        // lagu) tetap dibiarkan memutar sampai tuntas versinya
        // sendiri. Push "ended" cuma dikirim untuk stop manual.
        if (!($validated['natural'] ?? false)) {
            try {
                $this->fcmPushService->sendAudioBroadcastEnded(
                    $validated['room_id'],
                    $validated['outlet_ids'] ?? []
                );
            } catch (\Throwable $e) {
                // Sudah di-log di dalam FcmPushService.
            }
        }

        return response()->json([
            'message' => 'Audio broadcast ended sent',
        ]);
    }

    // ============================================================
    // OPERATOR → OUTLET
    // AUDIO WEBRTC OFFER
    // ============================================================

    public function offer(Request $request)
    {
        $validated = $request->validate([
            'outlet_id' => ['required', 'integer'],
            'room_id' => ['required', 'string'],
            'offer' => ['required', 'array'],
        ]);

        broadcast(
            new AudioWebRTCOffer(
                $validated['outlet_id'],
                $validated['room_id'],
                $validated['offer']
            )
        );

        return response()->json([
            'message' => 'Audio WebRTC offer sent',
        ]);
    }

    // ============================================================
    // OUTLET → OPERATOR
    // AUDIO WEBRTC ANSWER
    // ============================================================

    public function answer(Request $request)
    {
        $validated = $request->validate([
            'outlet_id' => ['required', 'integer'],
            'room_id' => ['required', 'string'],
            'answer' => ['required', 'array'],
        ]);

        broadcast(
            new AudioWebRTCAnswer(
                $validated['outlet_id'],
                $validated['room_id'],
                $validated['answer']
            )
        );

        return response()->json([
            'message' => 'Audio WebRTC answer sent',
        ]);
    }

    // ============================================================
    // OUTLET → OPERATOR
    // AUDIO WEBRTC ICE
    // ============================================================

    public function ice(Request $request)
    {
        $validated = $request->validate([
            'outlet_id' => ['required', 'integer'],
            'room_id' => ['required', 'string'],
            'candidate' => ['required', 'array'],
        ]);

        broadcast(
            new AudioWebRTCIceCandidate(
                $validated['outlet_id'],
                $validated['room_id'],
                $validated['candidate']
            )
        );

        return response()->json([
            'message' => 'Audio WebRTC ICE sent',
        ]);
    }

    // ============================================================
    // OPERATOR → OUTLET
    // AUDIO WEBRTC ICE
    // ============================================================

    public function operatorIce(Request $request)
    {
        $validated = $request->validate([
            'outlet_id' => ['required', 'integer'],
            'room_id' => ['required', 'string'],
            'candidate' => ['required', 'array'],
        ]);

        broadcast(
            new AudioWebRTCOperatorIceCandidate(
                $validated['outlet_id'],
                $validated['room_id'],
                $validated['candidate']
            )
        );

        return response()->json([
            'message' => 'Audio WebRTC operator ICE sent',
        ]);
    }
}
