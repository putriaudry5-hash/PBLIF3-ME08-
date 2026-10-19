<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user('web');

        if (!$user) {
            return response()->json([
                'message' => 'Silakan login terlebih dahulu.'
            ], 401);
        }

        if (
            $user->role !== 'admin' ||
            $user->status !== 'aktif'
        ) {
            return response()->json([
                'message' => 'Akses hanya untuk Admin aktif.'
            ], 403);
        }

        return $next($request);
    }
}
