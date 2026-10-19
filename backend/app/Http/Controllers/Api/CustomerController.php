<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CustomerController extends Controller
{
    private function nameColumn(): string
    {
        return Schema::hasColumn('users', 'name')
            ? 'name'
            : 'nama';
    }

    private function formatCustomer($user): array
    {
        return [
            'id' => $user->id,
            'nama' => $user->nama,
            'email' => $user->email,
            'status' => strtolower($user->status) === 'aktif'
                ? 'Aktif'
                : 'Nonaktif',
            'tanggalBergabung' => $user->created_at
                ? date('d/m/Y', strtotime($user->created_at))
                : '-'
        ];
    }

    public function index()
    {
        $nameColumn = $this->nameColumn();

        $users = DB::table('users')
            ->where('role', 'pelanggan')
            ->select(
                'id',
                DB::raw("`{$nameColumn}` as nama"),
                'email',
                'status',
                'created_at'
            )
            ->orderByDesc('id')
            ->get();

        return response()->json([
            'data' => $users->map(
                fn ($user) => $this->formatCustomer($user)
            )
        ]);
    }

    public function toggleStatus(int $id)
    {
        $nameColumn = $this->nameColumn();

        $result = DB::transaction(function () use ($id, $nameColumn) {
            $user = DB::table('users')
                ->where('id', $id)
                ->where('role', 'pelanggan')
                ->lockForUpdate()
                ->first();

            if (!$user) {
                return null;
            }

            $newStatus = strtolower($user->status) === 'aktif'
                ? 'nonaktif'
                : 'aktif';

            DB::table('users')
                ->where('id', $id)
                ->where('role', 'pelanggan')
                ->update([
                    'status' => $newStatus,
                    'updated_at' => now()
                ]);

            return DB::table('users')
                ->where('id', $id)
                ->select(
                    'id',
                    DB::raw("`{$nameColumn}` as nama"),
                    'email',
                    'status',
                    'created_at'
                )
                ->first();
        });

        if (!$result) {
            return response()->json([
                'message' => 'Pelanggan tidak ditemukan.'
            ], 404);
        }

        return response()->json([
            'message' => 'Status pelanggan berhasil diperbarui.',
            'data' => $this->formatCustomer($result)
        ]);
    }
}
