<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class InternalTenantMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::guard('internal')->user();

        if (!$user) {
            return redirect()
                ->route('login.internal')
                ->with('login_error', 'Silakan login terlebih dahulu.');
        }

        if (strtolower((string) $user->status) !== 'aktif') {
            Auth::guard('internal')->logout();

            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()
                ->route('login.internal')
                ->with('login_error', 'Akun Anda sedang nonaktif.');
        }

        if (strtolower((string) $user->role) !== 'tenant') {
            return redirect()->route('admin.dashboard');
        }

        if (!$user->tenant_id) {
            Auth::guard('internal')->logout();

            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()
                ->route('login.internal')
                ->with(
                    'login_error',
                    'Akun Tenant belum terhubung dengan data tenant.'
                );
        }

        return $next($request);
    }
}