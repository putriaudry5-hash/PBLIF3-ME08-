<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Google\Client as GoogleClient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class GoogleCustomerAuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'credential' => ['required', 'string'],
        ]);

        $clientId = config('services.google.client_id');

        if (!$clientId) {
            return response()->json([
                'message' => 'Google Client ID belum dikonfigurasi di backend.',
            ], 500);
        }

        // Verifikasi token yang diterima dari Google.
        $google = new GoogleClient([
            'client_id' => $clientId,
        ]);

        try {
            $googleUser = $google->verifyIdToken(
                $request->input('credential')
            );
        } catch (\Throwable $e) {
            $googleUser = false;
        }

        if (!$googleUser) {
            return response()->json([
                'message' => 'Login Google gagal diverifikasi. Silakan coba lagi.',
            ], 401);
        }

        if (
            empty($googleUser['sub']) ||
            empty($googleUser['email']) ||
            empty($googleUser['email_verified']) ||
            $googleUser['email_verified'] !== true
        ) {
            return response()->json([
                'message' => 'Akun Google belum memenuhi persyaratan verifikasi.',
            ], 401);
        }

        $googleId = $googleUser['sub'];
        $email = $googleUser['email'];
        $name = $googleUser['name'] ?? $email;

        // Cari pelanggan berdasarkan Google ID.
        $user = User::where('google_id', $googleId)->first();

        if (!$user) {
            // Periksa apakah email sudah digunakan akun lain.
            $existingUser = User::where('email', $email)->first();

            if ($existingUser) {
                // Jangan menghubungkan akun admin/tenant ke login pelanggan.
                if ($existingUser->role !== 'pelanggan') {
                    return response()->json([
                        'message' => 'Email ini terdaftar sebagai akun internal. Gunakan halaman login internal.',
                    ], 403);
                }

                // Hubungkan Google hanya jika akun pelanggan belum memiliki Google ID.
                if ($existingUser->google_id !== null) {
                    return response()->json([
                        'message' => 'Email ini sudah terhubung dengan akun Google lain.',
                    ], 409);
                }

                $existingUser->google_id = $googleId;
                $existingUser->email_verified_at = now();
                $existingUser->save();

                $user = $existingUser;
            } else {
                // Buat pelanggan baru.
                $user = User::create([
                    'name' => $name,
                    'email' => $email,
                    'password' => Hash::make(Str::random(64)),
                    'google_id' => $googleId,
                    'role' => 'pelanggan',
                    'status' => 'aktif',
                    'email_verified_at' => now(),
                ]);
            }
        }

        if ($user->role !== 'pelanggan') {
            return response()->json([
                'message' => 'Akun ini bukan akun pelanggan.',
            ], 403);
        }

        if ($user->status !== 'aktif') {
            return response()->json([
                'message' => 'Akun pelanggan sedang nonaktif.',
            ], 403);
        }

        // Berikan token API, bukan login berbasis session Laravel.
        $token = $user->createToken(
            'google-customer',
            ['customer']
        )->plainTextToken;

        return response()->json([
            'message' => 'Login Google berhasil.',
            'token' => $token,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
            ],
        ]);
    }
}
