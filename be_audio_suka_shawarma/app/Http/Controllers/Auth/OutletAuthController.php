<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Outlet\OutletAuthRequest;
use App\Service\Outlet\OutletAuthService;
use Illuminate\Http\Request;

class OutletAuthController extends Controller
{
    public function __construct(
        protected OutletAuthService $outletAuthService
    ) {}

    public function connect(OutletAuthRequest $request)
    {

        $result = $this->outletAuthService->connect($request->validated());

        return response()->json([
            'message' => 'Outlet berhasil terhubung',
            'data' => $result,
        ]);
    }

    public function index()
    {
        $result = $this->outletAuthService->index();

        return response()->json([
            'message' => 'Outlet berhasil diambil',
            'data' => $result,
        ]);
    }

    public function show(int $id)
    {
        $result = $this->outletAuthService->show($id);

        return response()->json([
            'message' => 'Outlet detail berhasil diambil',
            'data' => $result,
        ]);
    }

    // ============================================================
    // UPDATE FCM TOKEN
    //
    // Dipanggil outlet (Flutter) setiap kali FCM token didapat/
    // berubah, supaya backend bisa kirim push notification untuk
    // membangunkan app yang sudah di-kill saat operator broadcast.
    // ============================================================

    public function updateFcmToken(Request $request)
    {
        $validated = $request->validate([
            'outlet_id' => ['required', 'integer', 'exists:outlets,id'],
            'fcm_token' => ['required', 'string'],
        ]);

        $this->outletAuthService->updateFcmToken(
            $validated['outlet_id'],
            $validated['fcm_token']
        );

        return response()->json([
            'message' => 'FCM token berhasil disimpan',
        ]);
    }

    public function logout()
    {
        $this->outletAuthService->logout();

        return response()->json([
            'message' => 'Outlet berhasil logout.',
        ]);
    }
}
