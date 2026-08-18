<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class OutletJwtMiddleware
{
    public function handle(
        Request $request,
        Closure $next
    ): Response {

        try {

            $outlet = auth('outlet')->user();

            if (!$outlet) {
                return response()->json([
                    'message' => 'Outlet tidak ditemukan',
                ], 401);
            }

            $request->attributes->set(
                'outlet',
                $outlet
            );

            return $next($request);
        } catch (\Throwable $e) {

            return response()->json([
                'message' => $e->getMessage(),
                'exception' => get_class($e),
            ], 401);
        }
    }
}
