<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InternalLoginController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'email' => [
                'required',
                'email'
            ],
            'password' => [
                'required',
                'string'
            ],
        ]);

        $credentials = [
            'email' => $request->email,
            'password' => $request->password,
            'status' => 'aktif',
        ];

        $remember = $request->boolean('remember');

        if (!Auth::guard('internal')->attempt(
            $credentials,
            $remember
        )) {
            return back()
                ->withInput(
                    $request->only('email')
                )
                ->with(
                    'login_error',
                    'Email atau password tidak sesuai.'
                );
        }

        $request->session()->regenerate();

        $user = Auth::guard('internal')->user();

        if ($user->role === 'admin') {
            return redirect()
                ->route('admin.dashboard');
        }

        if ($user->role === 'tenant') {
            return redirect()
                ->route('tenant.dashboard');
        }

        Auth::guard('internal')->logout();

        return redirect()
            ->route('login.internal')
            ->with(
                'login_error',
                'Role akun tidak dikenali.'
            );
    }

    public function destroy(Request $request)
    {
        Auth::guard('internal')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()
            ->route('login.internal');
    }
}