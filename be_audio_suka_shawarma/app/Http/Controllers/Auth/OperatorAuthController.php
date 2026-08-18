<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\OperatorLoginRequest;
use App\Service\Auth\OperatorAuthService;
use Illuminate\Http\Request;

class OperatorAuthController extends Controller
{
    public function __construct(
        protected OperatorAuthService $operatorAuthService
    ) {}

    public function login(OperatorLoginRequest $request)
    {
        $result = $this->operatorAuthService->login($request->validated());

        return response()->json([
            'message' => 'Login Berhasil',
            'data' => $result,
        ]);
    }

    public function logout(Request $request)
    {
        $this->operatorAuthService->logout();

        return response()->json([
            'message' => 'Logout berhasil',
        ]);
    }
}
