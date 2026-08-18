<?php

namespace App\Service\Push;

use App\Models\Outlet;
use Illuminate\Contracts\Container\Container;
use Illuminate\Support\Facades\Log;
use Kreait\Firebase\Contract\Messaging;
use Kreait\Firebase\Messaging\AndroidConfig;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\MulticastSendReport;
use Throwable;

/**
 * Kirim push notification (FCM) ke outlet, dipakai supaya siaran
 * audio-file tetap bisa diputar di background/saat app di-kill -
 * lihat BackgroundAudioHandler & FcmService di sisi Flutter.
 *
 * SENGAJA pakai data message (bukan notification message), supaya
 * Android bisa membangunkan headless Flutter engine walau app
 * sudah di-kill total, dan supaya kita yang mengatur sendiri kapan
 * notifikasi ditampilkan (lewat foreground service audio_service).
 */
class FcmPushService
{
    public function __construct(
        protected Container $container
    ) {}

    public function sendLiveBroadcastStarted(
        string $roomId,
        array $outletIds = []
    ): void {
        $tokens = $this->tokensFor($outletIds);

        if (empty($tokens)) {
            return;
        }

        $this->sendData($tokens, [
            'type' => 'live_broadcast_started',
            'room_id' => $roomId,
        ]);
    }

    public function sendLiveBroadcastEnded(
        string $roomId,
        array $outletIds = []
    ): void {
        $tokens = $this->tokensFor($outletIds);

        if (empty($tokens)) {
            return;
        }

        $this->sendData($tokens, [
            'type' => 'live_broadcast_ended',
            'room_id' => $roomId,
        ]);
    }

    public function sendAudioBroadcastStarted(
        string $roomId,
        string $audioUrl,
        string $audioName,
        array $outletIds = []
    ): void {
        $tokens = $this->tokensFor($outletIds);

        if (empty($tokens)) {
            return;
        }

        $this->sendData($tokens, [
            'type' => 'audio_broadcast_started',
            'room_id' => $roomId,
            'audio_url' => $audioUrl,
            'audio_name' => $audioName,
        ]);
    }

    public function sendAudioBroadcastEnded(
        string $roomId,
        array $outletIds = []
    ): void {
        $tokens = $this->tokensFor($outletIds);

        if (empty($tokens)) {
            return;
        }

        $this->sendData($tokens, [
            'type' => 'audio_broadcast_ended',
            'room_id' => $roomId,
        ]);
    }

    /**
     * Ambil fcm_token outlet. Kalau $outletIds kosong, kirim ke
     * SEMUA outlet yang punya token (dipakai kalau caller tidak
     * tahu daftar target eksplisit).
     */
    protected function tokensFor(array $outletIds): array
    {
        $query = Outlet::query()->whereNotNull('fcm_token');

        if (!empty($outletIds)) {
            $query->whereIn('id', $outletIds);
        }

        return $query->pluck('fcm_token')
            ->filter()
            ->unique()
            ->values()
            ->all();
    }

    protected function sendData(array $tokens, array $data): void
    {
        // Semua value data message HARUS string untuk FCM.
        $stringData = array_map('strval', $data);

        $message = CloudMessage::new()
            ->withData($stringData)
            ->withAndroidConfig(
                AndroidConfig::new()->withHighMessagePriority()
            );

        foreach (array_chunk($tokens, 500) as $chunk) {
            try {
                // Resolve Messaging DI SINI (bukan lewat constructor
                // injection) supaya kredensial Firebase yang belum
                // ada/invalid tidak pernah menggagalkan endpoint
                // broadcast utama - cuma push notif-nya saja yang
                // gagal, siaran audio/live tetap jalan normal.
                $messaging = $this->container->make(Messaging::class);

                $report = $messaging->sendMulticast(
                    $message,
                    $chunk
                );

                $this->pruneInvalidTokens($report);
            } catch (Throwable $e) {
                Log::error('FCM push gagal dikirim', [
                    'error' => $e->getMessage(),
                    'data' => $stringData,
                ]);
            }
        }
    }

    /**
     * Bersihkan token yang sudah invalid/expired (uninstall app,
     * dsb) supaya tidak terus dicoba kirim ke situ.
     */
    protected function pruneInvalidTokens(MulticastSendReport $report): void
    {
        $invalidTokens = $report->invalidTokens();

        if (empty($invalidTokens)) {
            return;
        }

        Outlet::whereIn('fcm_token', $invalidTokens)
            ->update(['fcm_token' => null]);
    }
}
