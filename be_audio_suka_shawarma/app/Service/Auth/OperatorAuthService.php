<?php

namespace App\Service\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

class OperatorAuthService
{
    /**
     * Login operator.
     */
    public function login(array $data): array
    {
        $user = User::where('email', $data['email'])->first();

        if (!$user || !Hash::check($data['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => [
                    'Email atau password salah',
                ],
            ]);
        }

        if (!in_array($user->role, ['operator', 'admin'])) {
            throw ValidationException::withMessages([
                'email' => [
                    'Akun tidak memiliki akses operator',
                ],
            ]);
        }

        $token = JWTAuth::fromUser($user);

        return [
            'token' => $token,
            'token_type' => 'Bearer',
            'user' => $user,
        ];
    }

    /**
     * Logout operator.
     */
    public function logout(): void
    {
        JWTAuth::invalidate(
            JWTAuth::getToken()
        );
    }
}
