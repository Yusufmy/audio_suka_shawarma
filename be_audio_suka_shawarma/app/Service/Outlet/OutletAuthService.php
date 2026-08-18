<?php

namespace App\Service\Outlet;

use App\Events\OutletPresenceUpdated;
use App\Models\Outlet;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class OutletAuthService
{
    /**
     * App outlet yang di-kill paksa (bukan ditutup normal) tidak
     * akan pernah sempat lapor "offline" - satu-satunya cara tahu
     * itu benar-benar mati adalah heartbeat-nya sudah lama tidak
     * update. Threshold ini harus lebih besar dari interval
     * heartbeat Flutter (15 detik) supaya tidak salah anggap mati
     * gara-gara satu heartbeat yang telat/hilang.
     */
    private const STALE_SECONDS = 30;

    /**
     * Get All Outlet
     */
    public function index(): array
    {
        $outlets = Outlet::query()
            ->orderBy('id', 'asc')
            ->get();

        return $outlets->map(function ($outlet) {
            $this->correctIfStale($outlet);

            return [
                'id' => $outlet->id,
                'code' => $outlet->code,
                'name' => $outlet->name,
                'status' => $outlet->status,
                'presence' => $outlet->presence,
                'is_busy' => $outlet->is_busy,
                'paired_at' => $outlet->paired_at,
                'last_seen_at' => $outlet->last_seen_at,
                'device_info' => $outlet->device_info,
                'created_at' => $outlet->created_at,
                'updated_at' => $outlet->updated_at,
            ];
        })->toArray();
    }

    /**
     * Kalau outlet mengaku masih foreground/background tapi
     * heartbeat-nya sudah basi, koreksi jadi offline - sekalian
     * broadcast supaya semua dashboard operator yang sedang buka
     * ikut ter-update, bukan cuma yang baru fetch ini.
     */
    private function correctIfStale(Outlet $outlet): void
    {
        if ($outlet->presence === 'offline' || !$outlet->last_seen_at) {
            return;
        }

        if ($outlet->last_seen_at->diffInSeconds(now()) <= self::STALE_SECONDS) {
            return;
        }

        $outlet->update([
            'status' => 'offline',
            'presence' => 'offline',
        ]);

        broadcast(
            new OutletPresenceUpdated(
                $outlet->id,
                'offline',
                'offline',
                $outlet->last_seen_at?->toISOString(),
            )
        );
    }

    /**
     * Get outlet by ID.
     */
    public function show(int $id): array
    {
        $outlet = Outlet::find($id);

        if (!$outlet) {
            throw new NotFoundHttpException(
                'Outlet tidak ditemukan.'
            );
        }

        return [
            'id' => $outlet->id,
            'code' => $outlet->code,
            'name' => $outlet->name,
            'status' => $outlet->status,
            'is_busy' => $outlet->is_busy,
            'paired_at' => $outlet->paired_at,
            'last_seen_at' => $outlet->last_seen_at,
            'device_info' => $outlet->device_info,
            'created_at' => $outlet->created_at,
            'updated_at' => $outlet->updated_at,
        ];
    }

    /**
     * Connect / pairing outlet.
     */
    public function connect(array $data): array
    {
        return DB::transaction(function () use ($data) {

            $outlet = Outlet::where('name', $data['name'])
                ->lockForUpdate()
                ->first();

            if (!$outlet) {
                throw new NotFoundHttpException(
                    'Outlet tidak ditemukan.'
                );
            }

            // ============================================================
            // DEVICE DARI REQUEST
            // ============================================================

            $requestDeviceId = $data['device_info']['device_id'];

            // ============================================================
            // DEVICE YANG SUDAH TERSIMPAN DI DATABASE
            // ============================================================

            $databaseDeviceId = data_get(
                $outlet->device_info,
                'device_id'
            );

            Log::info('OUTLET CONNECT DEVICE CHECK', [
                'outlet_id' => $outlet->id,
                'outlet_name' => $outlet->name,

                'database_device_id' => $databaseDeviceId,
                'request_device_id' => $requestDeviceId,

                'status' => $outlet->status,
                'presence' => $outlet->presence,
                'last_seen_at' => $outlet->last_seen_at,
            ]);

            // ============================================================
            // JIKA SUDAH ADA DEVICE
            // ============================================================

            if ($databaseDeviceId !== null) {

                // ========================================================
                // DEVICE SAMA
                // Boleh reconnect
                // ========================================================

                if ($databaseDeviceId === $requestDeviceId) {

                    Log::info('OUTLET CONNECT: DEVICE YANG SAMA', [
                        'device_id' => $requestDeviceId,
                    ]);
                } else {

                    // ====================================================
                    // DEVICE BERBEDA
                    // CEK APAKAH DEVICE LAMA MASIH AKTIF
                    // ====================================================

                    $isStillActive =
                        $outlet->status === 'online'
                        &&
                        $outlet->last_seen_at
                        &&
                        $outlet->last_seen_at->gt(
                            now()->subSeconds(self::STALE_SECONDS)
                        );

                    Log::warning('OUTLET CONNECT: DEVICE BERBEDA', [
                        'database_device_id' => $databaseDeviceId,
                        'request_device_id' => $requestDeviceId,
                        'is_still_active' => $isStillActive,
                    ]);

                    if ($isStillActive) {

                        throw new ConflictHttpException(
                            'Outlet sudah terhubung di perangkat lain.'
                        );
                    }

                    // Device lama sudah stale/offline.
                    // Device baru boleh mengambil alih.
                }
            }

            // ============================================================
            // BARU DI SINI BOLEH UPDATE DEVICE
            // ============================================================

            $outlet->update([
                'status' => 'online',
                'presence' => 'foreground',
                'paired_at' => now(),
                'last_seen_at' => now(),
                'device_info' => $data['device_info'],
            ]);

            Log::info('OUTLET CONNECT: DEVICE BERHASIL DISIMPAN', [
                'outlet_id' => $outlet->id,
                'device_id' => $requestDeviceId,
            ]);

            // ============================================================
            // JWT
            // ============================================================

            $token = JWTAuth::fromUser($outlet);

            return [
                'token' => $token,
                'token_type' => 'Bearer',

                'outlet' => [
                    'id' => $outlet->id,
                    'code' => $outlet->code,
                    'name' => $outlet->name,
                    'status' => $outlet->status,
                    'is_busy' => $outlet->is_busy,
                    'paired_at' => $outlet->paired_at,
                    'last_seen_at' => $outlet->last_seen_at,
                    'device_info' => $outlet->device_info,
                ],
            ];
        });
    }
    /**
     * Logout / disconnect outlet.
     */
    public function logout(): void
    {
        Log::info('========== OUTLET LOGOUT START ==========');

        $token = JWTAuth::getToken();

        Log::info('OUTLET LOGOUT TOKEN', [
            'has_token' => $token !== null,
        ]);

        if (!$token) {
            Log::warning('OUTLET LOGOUT TOKEN TIDAK ADA');

            throw new UnauthorizedHttpException(
                'Bearer',
                'Token outlet tidak ditemukan.'
            );
        }

        Log::info('OUTLET LOGOUT SEBELUM PAYLOAD');

        $payload = JWTAuth::setToken($token)->getPayload();

        Log::info('OUTLET LOGOUT PAYLOAD BERHASIL', [
            'payload' => $payload->toArray(),
        ]);

        $outletId = $payload->get('outlet_id');

        Log::info('OUTLET LOGOUT OUTLET ID', [
            'outlet_id' => $outletId,
        ]);

        if (!$outletId) {
            throw new UnauthorizedHttpException(
                'Bearer',
                'Identitas outlet tidak ditemukan di token.'
            );
        }

        $outlet = Outlet::where('id', $outletId)->first();

        Log::info('OUTLET LOGOUT DATABASE', [
            'database' => DB::connection()->getDatabaseName(),
            'outlet' => $outlet?->toArray(),
        ]);

        if (!$outlet) {
            throw new NotFoundHttpException(
                'Outlet tidak ditemukan.'
            );
        }

        DB::transaction(function () use ($outlet, $token) {

            $outlet->update([
                'status' => 'offline',
                'presence' => 'offline',
                'last_seen_at' => now(),
                'paired_at' => null,
            ]);

            broadcast(
                new OutletPresenceUpdated(
                    $outlet->id,
                    'offline',
                    'offline',
                    $outlet->last_seen_at?->toISOString(),
                )
            );

            JWTAuth::setToken($token)->invalidate();
        });

        Log::info('========== OUTLET LOGOUT SUCCESS ==========');
    }

    private function assertDeviceIsAllowed(
        Outlet $outlet,
        string $deviceId
    ): void {

        $currentDeviceId = $outlet->device_info['device_id'] ?? null;

        \Illuminate\Support\Facades\Log::info(
            'CHECK OUTLET DEVICE LOGIN',
            [
                'outlet_id' => $outlet->id,
                'outlet_name' => $outlet->name,
                'status' => $outlet->status,
                'presence' => $outlet->presence,
                'current_device_id' => $currentDeviceId,
                'request_device_id' => $deviceId,
                'last_seen_at' => $outlet->last_seen_at,
            ]
        );

        // ============================================================
        // 1. BELUM PERNAH ADA DEVICE
        // ============================================================

        if (!$currentDeviceId) {
            return;
        }

        // ============================================================
        // 2. DEVICE YANG SAMA
        // ============================================================

        if ($currentDeviceId === $deviceId) {
            return;
        }

        // ============================================================
        // 3. DEVICE BERBEDA
        //
        // Cek apakah device lama masih aktif berdasarkan heartbeat.
        // ============================================================

        $isStillActive =
            $outlet->last_seen_at &&
            $outlet->last_seen_at->gt(
                now()->subSeconds(self::STALE_SECONDS)
            );

        if ($isStillActive) {

            throw new ConflictHttpException(
                'Outlet sedang digunakan di perangkat lain. '
                    . 'Silakan logout terlebih dahulu dari perangkat sebelumnya.'
            );
        }

        // ============================================================
        // 4. DEVICE LAMA SUDAH TIDAK AKTIF
        // Boleh mengambil alih.
        // ============================================================
    }

    /**
     * Simpan/update FCM token milik outlet, dipakai backend untuk
     * kirim push notification (siaran background saat app di-kill).
     */
    public function updateFcmToken(int $outletId, string $fcmToken): void
    {
        $outlet = Outlet::find($outletId);

        if (!$outlet) {
            throw new NotFoundHttpException(
                'Outlet tidak ditemukan.'
            );
        }

        $outlet->update([
            'fcm_token' => $fcmToken,
        ]);
    }
}
